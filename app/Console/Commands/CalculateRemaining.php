<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Models\UserConsumption;
use App\Models\Order;
use App\Models\Address;
use App\Events\Api\OnAlmostOutOfWater;
use Illuminate\Support\Facades\DB;

class CalculateRemaining extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:remaining';

    /**
     * @var int $daysLimit
     */
    protected $daysLimit = 2;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (Address::all() as $address) {
            $this->calculatePerAddress($address);
        }
    }
    public function calculatePerAddress(Address $address)
    {
        $latestOrder = Order::where('address_id', $address->id)
            ->where('status', Order::STATUS_COMPLETED)
            ->latest('created_at')->first();
        if (!$latestOrder) return;
        if (!(float)$latestOrder->litrs) return;

        switch ($latestOrder->last_saved_type)
        {
            case Order::LAST_SAVED_SYSTEM:
                $this->handleSystemOrder($latestOrder);
                break;
            case Order::LAST_SAVED_USER:
                $this->handleUserOrder($latestOrder);
                break;
        }
    }

    public function handleUserOrder(Order $order)
    {
        $newVal = $order->litrs - $order->address->consumption;
        if ($newVal < 0) {
            $newVal = 0;
            $order->last_saved_type = Order::LAST_SAVED_SYSTEM;
        }
        $order->litrs = $newVal;
        $order->save();
    }

    public function handleSystemOrder(Order $order)
    {
        $consumptionValue = (float)$order->address->consumption;
        $order->litrs = (float)$order->litrs;
        if ($consumptionValue <= 0) return;

        if ($order->litrs >= $consumptionValue) {
            $order->litrs -= $consumptionValue;
            $order->save();
        } else {
            $order->litrs = 0;
            $order->save();
        }

        if ($consumptionValue * $this->daysLimit >= $order->litrs) {
            event(new OnAlmostOutOfWater($order->user, $order->address));
        }
    }
}
