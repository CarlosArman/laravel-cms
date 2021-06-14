<?php

namespace App\Http\Middleware;

use App\Models\UserPermission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;


class EnsureUserRoleIsAllowedToAccess
{
    // dashboard, pages, navigation-menus

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $userRole = auth()->user()->role;
            $currentRouteName = Route::currentRouteName();

            if (UserPermission::isRoleHasRightToAccess($userRole, $currentRouteName) 
            || in_array($currentRouteName, $this->defaultUserAccessRole()[$userRole])) {
                return $next($request);
            } else {
                abort(403, 'Unauthorized Action.');
            }
        } catch (\Throwable $th) {
            abort(403, 'Unauthorized Action.');
        }
    }

    /**
     * The default user acces role.
     *
     * @return void
     */
    private function defaultUserAccessRole()
    {
        return [

            'admin' => [

                'user-permissions'
            ],
        ];
    }
}
