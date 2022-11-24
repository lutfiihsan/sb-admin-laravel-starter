<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\RoleHasPermission;
use App\Models\ModelHasPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $routeName = Route::currentRouteName();

        $permissionRegistered = Permission::where('name', $routeName)->first();
        if ($permissionRegistered) {
            $user = Auth::user();
            $roleId = $user->roles->pluck('id')->first();
            $rolePermissions = RoleHasPermission::where('role_id', $roleId)
                ->where('permission_id', $permissionRegistered->id)
                ->first();

            $userPermission = ModelHasPermission::where('model_id', $user->id)
                ->where('model_type', 'App\Models\User')
                ->where('permission_id', $permissionRegistered->id)
                ->first();

            if (!$userPermission and !$rolePermissions) abort(403);
        }

        return $next($request);
    }
}
