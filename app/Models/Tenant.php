<?php

namespace App\Models;

use App\Models\Interface\ConfigurableInterface;
use App\Models\Traits\ConfigurableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model implements ConfigurableInterface
{
    use HasFactory, ConfigurableTrait;

    protected $fillable = ['name', 'address'];
}
