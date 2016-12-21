<?php

namespace App\Console;

use App\Console\Commands\AdminManagerCommand;
use App\Console\Commands\GenerateClientsFile;
use App\Console\Commands\SyncChecksCommand;
use App\Console\Commands\SyncProductsCommand;
use App\Console\Commands\SyncShopsCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        AdminManagerCommand::class,
        GenerateClientsFile::class,
        SyncShopsCommand::class,
        SyncProductsCommand::class,
        SyncChecksCommand::class,
    ];

    /**
     * Define the application's command schedule.
     * to enable schedule add to cron: * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('sync:shops')->dailyAt('23:59');
        $schedule->command('sync:products')->dailyAt('23:59');
        $schedule->command('sync:checks')->cron('5,35 * * * *');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
