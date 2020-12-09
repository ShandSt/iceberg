<?php

namespace App\Console;

use App\Console\Commands\CheckOneCServerStatusCommand;
use App\Console\Commands\CheckOrderPaidStatus;
use App\Console\Commands\ExportOrders;
use App\Console\Commands\GeneratePrice;
use App\Console\Commands\OneCSyncCategories;
use App\Console\Commands\OneCSyncCities;
use App\Console\Commands\OneCSyncOrders;
use App\Console\Commands\OneCSyncProducts;
use App\Console\Commands\OneCSyncProductsNew;
use App\Console\Commands\OneCSyncTags;
use App\Console\Commands\OneCSyncUsers;
use App\Console\Commands\OneCSyncAddressConsumption;
use App\Console\Commands\CalculateRemaining;
use App\Console\Commands\PushToDevice;
use App\Console\Commands\SendPushForNewNews;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('1c:server:status')
            ->everyTenMinutes();

        $schedule->command('1c:sync:categories')
            ->everyThirtyMinutes();

        $schedule->command('1c:sync:tags')
            ->everyThirtyMinutes();

        $schedule->command('1c:sync:productsnew')
            ->everyThirtyMinutes();

//        $schedule->command('news:send:push')
//            ->everyMinute();

//        $schedule->command('1c:sync:orders')
//                 ->hourly();

        $schedule->command('1c:sync:address-consumption')
            ->daily();

        $schedule->command('1c:sync:cities')
            ->daily();

        $schedule->command('calculate:remaining')
            ->dailyAt('13:00');

        $schedule->command('db:backup --database=pgsql --destination=dropbox --destinationPath=/backups/iceberg-aqua --timestamp="d-m-Y h:i:s" --compression=gzip')
            ->daily();

        $schedule->command('export:orders')
            ->twiceDaily(config('export.first_hour'), config('export.second_hour'));

        $schedule->command('orders:check-paid-status')
            ->everyTenMinutes();

        $schedule->command('price:generate')
            ->hourly();

        $schedule->command('horizon:snapshot')
            ->everyFiveMinutes();

        $schedule->command('telescope:prune --hours=72')
            ->daily();

        $schedule->command('1c:resync:orders')
            ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
