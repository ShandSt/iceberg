<?php

namespace App\Console\Commands;

use App\Events\Api\OnOrderUpdated;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class ReSyncWithOneCFailedOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '1c:resync:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ReSync failed orders with 1C';

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
        foreach ($this->getSyncFailedOrders() as $order) {
            event(new OnOrderUpdated($order));
        }
    }

    private function getSyncFailedOrders(): Collection
    {
        return Order::whereNotNull('sync_failed_at')->where('status', '!=', 'Canceled')->get();
    }
}
