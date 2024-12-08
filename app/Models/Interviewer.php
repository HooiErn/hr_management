<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interviewer extends Model
{
    use HasFactory;
    protected $fillable = [
        'job_title',
        'candidate_id',
        'name',
        'age',
        'race',
        'gender',
        'phone_number',
        'email',
        'highest_education',
        'work_experiences',
        'message',
        'cv_upload',
        'interview_datetime',
        'ic_number',
    ];
}
