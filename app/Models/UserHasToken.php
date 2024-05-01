<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHasToken extends Model
{
    use HasFactory;
    protected $table = "user_has_tokens";
    protected $fillable = ['title','body','device_token','is_sent','user_id'];
}
