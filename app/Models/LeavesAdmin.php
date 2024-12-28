<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeavesAdmin extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'leave_type',
        'from_date',
        'to_date',
        'day',
        'leave_reason',
        'leave_status',
        'remaining_days'
    ];

    // Define the relationship with the Employee model
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'user_id'); // Assuming 'user_id' is the foreign key
    }
}
