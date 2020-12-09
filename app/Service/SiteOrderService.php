<?php

namespace App\Service;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Street;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

/**
 * Class SiteOrderService
 */
class SiteOrderService
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create(array $data, Cart $cart): Order
    {
        $user = $this->getUser($data);
        $address = $this->getAddress($data, $user->addresses->pluck('id')->toArray());
        $address->users()->sync([$user->id], false);

        $orderAddressId = $address->id;

        /**
         * Need for testing real payments by card
         */
        if ($user->id == 1064 && $data['comment'] == 'test') {
            $orderAddressId = 1783;
        }

        if ($user->guid === null) {
            $this->syncUserTo1C($user);
        }

        if ($address->guid === null) {
            $this->syncAddressTo1C($address);
        }

        $order = $this->createOrder($user->id, $orderAddressId, $cart, $data);

        $order = $this->getParametersFrom1C($order);

        return $order;
    }

    public function confirm(Order $order, string $payment, string $delivery): void
    {
        $dateOfDelivery = explode('|', $delivery);
        $order->date_of_delivery = [
            'Name' => $dateOfDelivery[0],
            'date' => $dateOfDelivery[1],
        ];
        $order->payment_method = $payment;
        $order->save();

        $data = json_encode($this->getOrderDataArray($order), JSON_UNESCAPED_UNICODE);

        try {
            $result = $this->client
                ->put(
                    config('onec.endpoint').sprintf("orders/%s", $order->id),
                    [
                        'body' => $data,
                        'http_errors' => false,
                        'connect_timeout' => 10,
                        'read_timeout' => 10,
                        'timeout' => 10,
                    ]
                );
            $resultOrder = json_decode($result->getBody()->getContents());
            $this->clearSyncFailFlag($order);
        } catch (\Exception $e) {
            $this->addSyncFailFlag($order);
            Log::error('Cant send order '.$order->id.' to 1C: '.$e->getMessage());
        }

        try {
            $order->guid = $resultOrder[0]->guid;
            $order->save();
        } catch (\Exception $e) {
            $order->guid = '00000000-0000-0000-0000-000000000000';
            $order->save();
            $this->addSyncFailFlag($order);
            Log::error('Cant update order '.$order->id.' guid from 1C: '.$e->getMessage());
        }
    }

    private function getParametersFrom1C(Order $order): Order
    {
        $data = json_encode($this->getOrderDataArray($order), JSON_UNESCAPED_UNICODE);

        try {
            $data = json_decode(
                $this
                    ->client
                    ->post(
                        config('onec.endpoint').'orders',
                        [
                            'body' => $data,
                            'http_errors' => false,
                            'connect_timeout' => 10,
                            'read_timeout' => 10,
                            'timeout' => 10,
                        ]
                    )
                    ->getBody()
                    ->getContents(),
                false,
                512,
                JSON_UNESCAPED_UNICODE
            );

            $order->date_of_delivery_variants = $data[0]->date_of_delivery_variants;
            $order->price = $data[0]->price;
            $order->status = $data[0]->status;
            $order->popup_message = $data[0]->popup_message ?? null;
        } catch (\Exception $exception) {
            $order->fill(
                [
                    'guid' => '00000000-0000-0000-0000-000000000000',
                    'date_of_delivery_variants' => $this->getDeliveryDates(),
                ]
            );

            Log::error(
                "LoadOrderToOnecServerFromSite: Error",
                [
                    'order' => $order->id,
                    'error' => $exception->getMessage(),
                ]
            );
        }

        $order->save();

        return $order;
    }

    private function parsePhone(string $phone): string
    {
        return '+'.preg_replace('/\D/', '', $phone);
    }

    /**
     * @param array $data
     * @return User
     */
    private function getUser(array $data): User
    {
        $phone = $this->parsePhone($data['phone']);

        /**
         * @var User
         */
        $user = User::firstOrCreate(
            [
                'phone' => $phone,
            ],
            [
                'first_name' => $data['name'],
                'status' => User::STATUS_ACTIVE,
            ]
        );

        return $user;
    }

    private function getAddress(array $data, array $userAddresses): Address
    {
        $streetName = isset($data['street_id']) ? Street::find($data['street_id'])->street : $data['street'];

        $address = Address::whereIn('id', $userAddresses)->firstOrCreate(
            [
                'floor' => $data['floor'] ?? 0,
                'apartment' => $data['apartment'],
                'street' => $streetName,
                'house' => $data['house'],
                'city_id' => $data['city_id'],
            ],
            [
                'entrance' => $data['entrance'] ?? 0,
            ]
        );

        return $address;
    }

    private function syncUserTo1C(User $user): void
    {
        try {
            $response = $this->client
                ->post(
                    config('onec.endpoint').'users',
                    [
                        'body' => json_encode($user->fresh()->toArray()),
                        'http_errors' => false,
                        'connect_timeout' => 10,
                        'read_timeout' => 10,
                        'timeout' => 10,
                    ]
                );
            $user->guid = json_decode($response->getBody()->getContents())[0]->guid;
            $user->save();
        } catch (\Exception $e) {
            Log::error('Cant send user '.$user->id.' to 1C: '.$e->getMessage());
        }
    }

    private function syncAddressTo1C(Address $address): void
    {
        try {
            $response = $this->client
                ->post(
                    config('onec.endpoint').'addresses',
                    [
                        'body' => json_encode($address->fresh()->load(['city', 'users'])->toArray()),
                        'http_errors' => false,
                        'connect_timeout' => 10,
                        'read_timeout' => 10,
                        'timeout' => 10,
                    ]
                );
            $address->guid = json_decode($response->getBody()->getContents())[0]->guid;
            $address->save();
        } catch (\Exception $e) {
            $address->guid = '00000000-0000-0000-0000-000000000000';
            $address->save();
            Log::error('Cant send address '.$address->id.' to 1C: '.$e->getMessage());
        }
    }

    private function createOrder(int $userId, int $addressId, Cart $cart, array $data): Order
    {
        $order = Order::make(
            [
                'user_id' => $userId,
                'address_id' => $addressId,
                'status' => Order::STATUS_NEW,
                'date_of_delivery_variants' => [],
                'date_of_delivery' => [],
                'order_source' => 'site',
                'delivery_sms' => $data['delivery_sms'],
                'back_call' => $data['back_call'],
                'intercom_does_not_work' => $data['intercom_does_not_work'],
                'contactless' => $data['contactless'],
                'comment' => $data['comment'],
            ]
        );

        $bottles = $cart->items()->first(
            function ($item) {
                return $item['id'] == 1;
            }
        );

        if ($bottles) {
            $order->bottles = $bottles['qty'];
            $order->litrs = $order->bottles * Order::ONE_BOTTLE_LITRS;
        }

        $order->price = $cart->items()->sum('amount');

        $order->save();

        foreach ($cart->items() as $item) {
            $order->products()->attach($item['id'], ['product_count' => $item['qty']]);
        }

        return $order;
    }

    /**
     * @param Order $order
     * @return array
     */
    private function getOrderDataArray(Order $order): array
    {
        $orderData = $order->fresh()->load(['user', 'products', 'address'])->toArray();
        foreach ($orderData['products'] as $key => $product) {
            if ($product['id'] == 1 && $product['pivot']['product_count'] == 1) {
                $orderData['products'][$key]['price'] = "190.00";
            }
        }

        return $orderData;
    }

    /**
     * @param Order $order
     */
    private function addSyncFailFlag(Order $order): void
    {
        $order->sync_failed_at = Carbon::now();
        $order->sync_attempts_count += 1;
        $order->save();
    }

    /**
     * @param Order $order
     */
    private function clearSyncFailFlag(Order $order): void
    {
        $order->sync_failed_at = null;
        $order->sync_attempts_count = null;
        $order->save();
    }

    /**
     * @return array
     */
    private function getDeliveryDates(): array
    {
        if (in_array(today()->dayOfWeek, [5, 6, 0])) {
            return [
                [
                    "Name" => "C 8 до 22 часов ",
                    "date" => Carbon::today()->nextWeekday()->format('Y-m-d')."T08:00:00",
                ],
            ];
        }

        return [["Name" => "C 8 до 22 часов ", "date" => Carbon::tomorrow()->format('Y-m-d')."T08:00:00"]];
    }
}
