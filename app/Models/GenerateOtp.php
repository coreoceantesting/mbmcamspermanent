<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenerateOtp extends Model
{
    use HasFactory;

    protected $fillable = ['mobile', 'otp', 'ip'];
}
