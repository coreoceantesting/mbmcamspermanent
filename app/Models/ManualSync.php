<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualSync extends BaseModel
{
    use HasFactory;

    protected $fillable = ['user_id', 'emp_code', 'from_date', 'to_date'];
}
