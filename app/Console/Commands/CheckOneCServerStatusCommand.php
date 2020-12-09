<?php

namespace App\Console\Commands;

use App\Service\OneC\Exception\SaveConfigException;
use App\Service\OneC\Server\OneCServer;
use Illuminate\Console\Command;

class CheckOneCServerStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '1c:server:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check OneC server status';


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
        $this->info("Start check one c server status");
        try {
            $status = $this->server->checkServerStatus();
        } catch (SaveConfigException $exception) {
            $this->error('SaveConfigException');
            $this->error($exception->getMessage());
        }

        if (200 === $status['server_status']) {
            $this->info("1c server working");
        } else {
            $this->error('1c server is down');
        }
    }
}
