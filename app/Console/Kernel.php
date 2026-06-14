<?php

namespace App\Console;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Settings;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\ProcessInvestmentRoi::class,
        \App\Console\Commands\CleanupOldNotifications::class,
        \App\Console\Commands\ProcessInvestmentPayouts::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Clean up old notifications once a week (keep last 30 days)
        $schedule->command('notifications:cleanup')->weekly();

        // Credit due investment payouts and process maturities (Phase 6)
        $schedule->command('investments:process-payouts')->daily();



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
