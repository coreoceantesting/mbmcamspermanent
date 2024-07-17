<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeWeekoff extends BaseModel
{
    use HasFactory;

    protected $fillable = [ 'user_id', 'weekoff_1', 'weekoff_2', 'start_of_week', 'end_of_week' ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
