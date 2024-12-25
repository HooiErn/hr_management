<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PastEmployee extends Model
{
    use HasFactory;

    // Define the table name (optional if the table name follows Laravel conventions)
    protected $table = 'past_employees';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'name',
        'email',
        'department',
        'role_name',
        'phone_number',
        'status',
        'resignation_date',
        'resignation_reason',
    ];
}
