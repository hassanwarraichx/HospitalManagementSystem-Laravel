<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    use HasFactory;
    protected $fillable = ['method', 'url', 'ip', 'user_agent', 'user_id'];


}
