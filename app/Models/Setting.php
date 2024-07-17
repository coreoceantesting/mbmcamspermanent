<?php

namespace App\Models;

use App\Models\Interface\ConfigurableInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Setting extends Model
{
    use HasFactory;

    const PAYROLL_DATE = "PAYROLL_DATE";
    const LATE_MARK_TIMING = "LATE_MARK_TIMING";
    const LATE_MARK_TIMING_DIVYANG = "LATE_MARK_TIMING_DIVYANG";
    const HALF_DAY_DURATION = "HALF_DAY_DURATION";
    const MIN_COMPLETION_HOUR = "MIN_COMPLETION_HOUR";

    protected $fillable = [
        'key','value', 'configurable_type', 'configurable_id', 'group', 'type'
    ];

    public function configurable(): MorphTo
    {
        return $this->morphTo();
    }


    public static function getValue(ConfigurableInterface $tenant, $key)
    {
        // $record = self::where('configurable_id', $tenant->id)
        //     ->where('configurable_type', $tenant instanceof Company ? 'App\Models\Company' : 'App\Models\Project')
        //     ->where('key', $key)
        //     ->first();
        // if ($record) {
        //     return $record->value;
        // }

        if ($tenant instanceof Tenant)
        {
            $record = self::where('configurable_id', $tenant->id)
                ->where('configurable_type', 'App\Models\Tenant')
                ->where('key', $key)
                ->first();
            return $record->value ?? null;
        }

        return null;
    }


    public static function getValues($tenant_id)
    {
        $tenants = [];
        // if ($tenant instanceof Tenant) {
            $tenants = self::where('configurable_id', $tenant_id)
                ->where('configurable_type', 'App\Models\Tenant')
                ->get()->toArray();
            $tenants = collect($tenants);
        // }

        return $tenants;
    }

    public static function setValue(ConfigurableInterface $tenant, $key, $value)
    {
        if ($value == null) {
            self::where('configurable_type', 'App\Models\Tenant')
                ->where('configurable_id', $tenant->id)
                ->where('key', $key)
                ->delete();
                return null;
        }
        return self::updateOrCreate([
            'configurable_type' => 'App\Models\Tenant',
            'configurable_id' => $tenant->id,
            'key' => $key,
            'group' => (self::getDefaults()->first(fn($setting) => $setting['key'] == $key))['group'] ?? '',
            'type' =>  (self::getDefaults()->first(fn($setting) => $setting['key'] == $key))['type'] ?? ''
        ], [
            'value' => $value,
        ]);
    }
}
