<?php

namespace App\Console\Commands;

use App\Events\Api\OnOrderPaymentStatusChanged;
use App\Events\Api\OnOrderUpdated;
use App\Models\Order;
use App\Service\Billing\Contracts\BillingServiceContract;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckOrderPaidStatus extends Command
{
    protected $signature = 'orders:check-paid-status';

    protected $description = 'Check paid status in Sberbank';

    /** @var BillingServiceContract */
    private $billing;

    public function __construct(BillingServiceContract $billing)
    {
        parent::__construct();

        $this->billing = $billing;
    }

    public function handle()
    {
        $orders = Order::whereNull('payment_status')
            ->whereNotNull('payment_hash')
            ->where('created_at', '<', Carbon::now()->subHour())
            ->get();
        Log::info('Start checking orders: '.$orders->count());

        foreach ($orders as $order) {
            $order->payment_hash = null;
            if ($this->billing->checkOrder($order->id)) {
                $order->payment_status = Order::STATUS_PAYED;
            } else {
                $order->payment_status = Order::STATUS_CANCELED;
                $order->status = Order::STATUS_CANCELED;
            }
            $order->save();
            Log::info('Check order: '.$order->id.', status: '.$order->payment_status);

            event(new OnOrderPaymentStatusChanged($order, $order->payment_status));
            event(new OnOrderUpdated($order));
        }
    }
}
