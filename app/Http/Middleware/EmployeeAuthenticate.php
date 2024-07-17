<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class EmployeeAuthenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('employee.login', ['device_type'=> 'mobile']);
    }

    protected function authenticate($request, array $guards)
    {
        if ($this->auth->guard('employee')->check())
        {
            return $this->auth->shouldUse('employee');
        }

        $this->unauthenticated($request, ['employee']);
    }
}
