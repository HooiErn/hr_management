<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PastEmployee extends Model
{
    protected $table = 'past_employees';
    
    protected $fillable = [
        'name',
        'department',
        'role_name',
        'email',
        'phone_number',
        'status',
        'resignation_date',
        'resignation_reason'
    ];

    protected $dates = [
        'resignation_date'
    ];
}
