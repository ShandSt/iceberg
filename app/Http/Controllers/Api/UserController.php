<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Events\Api\OnAppLaunch;
use App\Events\Api\OnBottlesUpdated;
use App\Events\Api\OnUserAdressAdded;
use App\Events\Api\OnUserPhoneUpdated;
use App\Events\Api\OnUserProfileUpdated;
use App\Http\Requests\Api\FilterOrdersRequest;
use App\Http\Requests\Api\UserAdressRequest;
use App\Http\Requests\Api\UserUpdateDeviceRequest;
use App\Http\Requests\Api\UserUpdatePhoneRequest;
use App\Http\Requests\Api\UserUpdateRequest;
use App\Models\Address;
use App\Models\Order;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    public function user(): JsonResponse
    {
        return response()->json(Auth::user()->load('settings', 'consumption', 'adress'));
    }

    public function update(UserUpdateRequest $request): JsonResponse
    {
        Auth::user()->first_name = $request->input('first_name');
        Auth::user()->last_name = $request->input('last_name');

        if ($request->input('company_name', false) !== false) {
            Auth::user()->company_name = $request->input('company_name');
        }

        if ($request->has('inn')) {
            Auth::user()->inn = $request->input('inn');
        }

        Auth::user()->save();

        event(new OnUserProfileUpdated(Auth::user(), Carbon::now()));


        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => [
                'user' => Auth::user(),
            ],
        ]);
    }


    public function water(Request $request)
    {
        /*$this->validate($request,[
            'address_id' => 'required|numeric',
        ]);*/

        /**
         * @var $user User
         */
        $user = Auth::user();

        $address_id = $request->input('address_id');

        $lastOrder = $user->orders()->where('address_id', $address_id)->where('status', Order::STATUS_COMPLETED)->latest()->first();
        if (!$lastOrder) {
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Cant find last order with address_id = ' . $address_id,
            ]);
        }
        $address = $user->adress()->where('address_id', $address_id)->first();
        if (!$address) {
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Cant find user address with address_id = ' . $address_id,
            ]);
        }
        $consumption = $address->consumption;

        return response()->json([
            'status' => Response::HTTP_OK,
            'bottles' => $lastOrder->bottles,
            'remaning' => $lastOrder->litrs,
            'consumption' => $consumption ?: '0.00',
        ]);
    }

    public function remaining(Request $request)
    {
        $this->validate($request, [
            'address_id' => 'required|numeric|exists:address,id',
            'remaining' => 'required|numeric',
        ]);

        $user = Auth::user();
        $latestOrder = $user->orders()
            ->where('address_id', $request->address_id)
            ->where('status', Order::STATUS_COMPLETED)
            ->latest('created_at')->first();

        if (!$latestOrder) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
                'data' => []
            ]);
        }

        $latestOrder->litrs = $request->remaining;
        $latestOrder->last_saved_type = Order::LAST_SAVED_USER;
        $latestOrder->save();

        return [
            'status' => Response::HTTP_OK,
            'data' => []
        ];
    }

    public function address(): JsonResponse
    {
        return response()->json(Auth::user()->adress);
    }


    public function addAddress(UserAdressRequest $request): JsonResponse
    {
        /**
         * @var $address Address
         */
        $address = Address::firstOrCreate($request->only(
            'street',
            'house',
            'entrance',
            'floor',
            'apartment',
            'comment',
            'city_id'
        ));

        if ($request->has('single_address')) {
            return response()->json($address);
        }

        $address->users()->sync([Auth::id()]);

        event(new OnUserAdressAdded($address, Auth::user()));

        return response()->json(Auth::user()->adress);
    }

    public function changeAddress(UserAdressRequest $request, int $id): JsonResponse
    {
        /**
         * @var $address Address
         */
        $address = Address::findOrFail($id);

        if (!$address->users()->wherePivot('user_id', Auth::id())->exists()) {
            throw new AccessDeniedHttpException("U can't change this address");
        }

        $address->update($request->input());

        return response()->json($address);
    }

    public function deleteAddress(int $id)
    {
        /**
         * @var $user User
         */
        $user = Auth::user();

        if (!$user->adress()->find($id)) {
            throw new NotFoundHttpException();
        }

        $user->adress()->detach($id);

        return;
    }

    public function setBottles(Request $request): JsonResponse
    {
        $this->validate($request, [
            'bottles' => 'required|integer',
        ]);

        Auth::user()->bottles = $request->input('bottles');
        Auth::user()->save();

        event(new OnBottlesUpdated(Auth::user()));

        return response()->json(Auth::user());
    }

    public function getBottles(): JsonResponse
    {
        return response()->json([
            'bottles' => Auth::user()->bottles,
        ]);
    }

    public function getOrders(FilterOrdersRequest $request): JsonResponse
    {
        $orders = Auth::user()
            ->orders()
            ->with(['products', 'address'])
            ->where(function ($query) use ($request) {
                if ($request->has('statuses')) {
                    $query->whereIn('status', explode(',', $request->get('statuses')));
                }
            })
            ->whereNotNull('payment_method')
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('pagesize', 10));

        return response()->json($orders);
    }

    public function updatePhone(UserUpdatePhoneRequest $request): JsonResponse
    {
        if (User::wherePhone($request->input('phone'))->first()) {
            throw new ConflictHttpException('Phone already exists');
        }

        Auth::user()->phone = $request->input('phone');
        Auth::user()->status = User::STATUS_NOT_ACTIVE;

        Auth::user()->save();

        event(new OnUserPhoneUpdated(Auth::user(), Carbon::now()));


        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => [
                'user' => Auth::user(),
            ],
        ]);
    }

    public function updateDevice(UserUpdateDeviceRequest $request): JsonResponse
    {
        $user = Auth::user();
        $user->devices()->first()->fill($request->input())->save();

        return response()->json([
            'status' => Response::HTTP_OK,
            'data' => [
                'user' => $user->load('devices'),
            ],
        ]);
    }

    public function syncOrders(): JsonResponse
    {
        event(new OnAppLaunch(Auth::user()));
        return response()->json([
            'status' => Response::HTTP_OK,
        ]);
    }

    public function changeDeviceToken(Request $request)
    {
        Auth::user() ? $user = Auth::user() : $user = User::find($request->input('id'));
        $device = $user->devices()->first();
        if (!$device) {
            $device = $user->devices()->create(['os' => 'android', 'device_id' => '', 'allow_push' => true]);
        }
        $device->token = $request->input('token');
        $user->save();

        return response()->json([
            'status' => Response::HTTP_OK
        ]);
    }


    public function activate(Request $request)
    {
        $user = User::where('phone', $request->input('phone'))->first();
        if (!$user) {
            return response()->json(['ok' => false, 'message' => 'Cant find user with phone: '.$request->input('phone')]);
        }
        $user->status = 'active';
        $user->save();
        return response()->json(['ok' => true]);
    }
}
