<?php

namespace App\Console\Commands;

use App\Jobs\SyncAddressConsumptionFromOneC;
use App\Service\OneC\Server\OneCServer;
use App\Models\Address;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class OneCSyncAddressConsumption extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '1c:sync:address-consumption';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync address consumption from 1c server';


    /**
     * @var OneCServer
     */
    private $server;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(OneCServer $server)
    {
        parent::__construct();

        $this->server = $server;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Address::chunk(100, function ($addresses) {
            foreach ($addresses as $address) {
                $this->info("Adding address {$address->id}");
                dispatch((new SyncAddressConsumptionFromOneC($address))->delay(random_int(2, 40)));
            }
        });
    }
}
