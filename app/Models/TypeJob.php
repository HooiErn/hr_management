<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'Full Time',
        'Part Time',
        'Internship',
        'Temporary',
        'Other',
    ];
}
