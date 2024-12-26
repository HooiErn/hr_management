<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Leave extends Model
{
    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Adjust 'user_id' if your foreign key is different
    }
}