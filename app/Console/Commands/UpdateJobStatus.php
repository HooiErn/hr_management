<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateJobStatus extends Command
{
    protected $signature = 'jobs:update-status';
    protected $description = 'Update job status from Open to Closed when expired';

    public function handle()
    {
        try {
            $now = Carbon::now();
            
            $affected = DB::table('add_jobs')
                ->where('status', 'Open')
                ->where(function($query) use ($now) {
                    $query->whereDate('expired_date', '<', $now->format('Y-m-d'))
                          ->orWhere(function($q) use ($now) {
                              $q->whereDate('expired_date', '=', $now->format('Y-m-d'))
                                ->whereTime('expired_date', '<=', $now->format('H:i:s'));
                          });
                })
                ->update(['status' => 'Closed']);

            \Log::info('Job statuses updated:', [
                'affected_rows' => $affected,
                'current_time' => $now->toDateTimeString()
            ]);
            
            $this->info("Successfully updated {$affected} expired job(s)");
        } catch (\Exception $e) {
            \Log::error('Error updating job statuses:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->error('Failed to update job statuses: ' . $e->getMessage());
        }
    }
} 