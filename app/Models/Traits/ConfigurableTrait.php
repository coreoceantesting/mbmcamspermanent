<?php

namespace App\Models\Traits;

use App\Models\Setting;

trait ConfigurableTrait {
    public function settings()
    {
        return $this->morphMany(Setting::class, "configurable");
    }
}
