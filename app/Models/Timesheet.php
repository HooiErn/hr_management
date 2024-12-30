<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'interviewer_id',
        'date',
        'scheduled_time',
        'status',
    ];

    public function interviewer()
    {
        return $this->belongsTo(Interviewer::class);
    }
}
