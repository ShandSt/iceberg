<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Davibennun\LaravelPushNotification\PushNotification;

class PushToDevice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:check';

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
        $deviceToken = "XI7ZuEzUaUPFKIA8ChdGOynd5pI9kjvBbiOOcselr7bpcgHNBSqxw2ONJKUB";
        $payload = [
            'aps' => [
                'alert' => [
                    'title' => "Тайтл",
                    'body' => "Боди",
                ],
                'type' => 'newsNotification',
                'sound' => 'default',
            ],
        ];
        $push = new PushNotification();
        $push->app('android')
            ->to($deviceToken)
            ->send('Hello World, i`m a push message', $payload);
    }
}
