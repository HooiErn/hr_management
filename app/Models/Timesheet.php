<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Timesheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'interviewer_id',
        'date',
        'scheduled_time',
        'status',
    ];

    // Add local scope to get only current and future schedules
    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_time', '>=', Carbon::now());
    }

    public function interviewer()
    {
        return $this->belongsTo(Interviewer::class);
    }
}
