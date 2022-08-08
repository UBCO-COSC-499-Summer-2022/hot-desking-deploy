<?php

namespace App\Console;

use App\Console\Commands\ClearOldBookings;
use App\Console\Commands\MonthlyBookingsUsedReset;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        MonthlyBookingsUsedReset::class,
        ClearOldBookings::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('reset:bookings')->monthly()->timezone('America/Los_Angeles');
        // $schedule->command('verify:bookings')->weeklyOn(1, '00:00')->timezone('America/Los_Angeles');
        $schedule->command('verify:bookings')->everyMinute()->timezone('America/Los_Angeles');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
