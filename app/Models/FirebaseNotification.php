<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirebaseNotification extends Model
{
    use HasFactory;
    protected $fillable = ['title','body','device_token','is_sent','user_id'];
}
