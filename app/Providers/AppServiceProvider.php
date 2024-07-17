<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Collection::macro('mergeByKey', function ($key) {
            return $this->groupBy($key)->map(function($group) {

                $filteredGroup = collect($group)->map(function($item) {
                    return collect($item)->reject(function($value, $key) {
                        return $value === null;
                    });
                });

                return array_merge(...$filteredGroup->toArray());
            })->values();
        });

        Carbon::macro('isTimeBefore', static function ($other) {
            return self::this()->format('Gis.u') < $other->format('Gis.u');
        });
    }
}
