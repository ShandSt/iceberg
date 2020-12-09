<?php

namespace App\Console\Commands;

use App\Jobs\SyncUserFromOneC;
use App\Service\OneC\Server\OneCServer;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class OneCSyncUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '1c:sync:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync users from 1c server';


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
        $status = json_decode(
            file_get_contents($this->server->getStatusFilePath()),
            true
        );

        if ($status['server_status'] != 200) {
            $this->error("Cannot start sync users. 1C server is down.");
            Log::error("OneCSynUsers",[
                'status' =>  $status,
            ]);

            return;
        }
        $this->info('Start adding users to queue');
        User::chunk(10000, function ($users) {
            foreach ($users as $user) {
                $this->info("Adding user {$user->id}");
                dispatch((new SyncUserFromOneC($user))->delay(random_int(10,120)));
            }
        });
        $this->info("All users added to queue.");
    }
}
