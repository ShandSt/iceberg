<?php

namespace App\Console\Commands;

use App\Jobs\SyncOrderFromOneC;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class OneCSyncOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '1c:sync:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync orders from 1C';

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
        foreach ($this->getOrders() as $order) {
            /**
             * @var $order Order
             */

            dispatch((new SyncOrderFromOneC($order))->delay(random_int(3,50)));
        }
    }

    private function getOrders(): Collection
    {
        return Order::whereNotIn('status', [Order::STATUS_COMPLETED, Order::STATUS_CANCELED])->where('created_at', '>', Carbon::now()->subWeeks(2))->get();
    }
}
