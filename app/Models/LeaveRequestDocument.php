<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequestDocument extends BaseModel
{
    use HasFactory;

    protected $fillable = [ 'leave_request_id', 'path' ];

    public function leaveRequest()
    {
        return $this->belongsTo(LeaveRequest::class);
    }

    protected function path() : Attribute
    {
        return Attribute::make(
            get: fn (string $value) => 'storage/'.$value
        );
    } 
}
