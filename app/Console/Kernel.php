<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\UpdateJobStatus::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Automatically close expired jobs daily at midnight
        $schedule->call(function () {
            $now = Carbon::now('Asia/Kuala_Lumpur')->startOfDay();
            $updated = DB::table('add_jobs')
                ->where('status', 'Open')
                ->where('expired_date', '<', $now)
                ->update(['status' => 'Closed']);

            \Log::info('Job status update scheduled task completed', [
                'jobs_closed' => $updated,
                'timestamp' => $now->toDateTimeString()
            ]);
        })->daily();
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
