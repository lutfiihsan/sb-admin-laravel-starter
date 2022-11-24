<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'users.index',
            'users.create',
            'users.store',
            'users.edit',
            'users.update',
            'users.destroy',
            'roles.index',
            'roles.create',
            'roles.store',
            'roles.edit',
            'roles.update',
            'roles.destroy',
            'permissions.index',
            'permissions.create',
            'permissions.store',
            'permissions.edit',
            'permissions.update',
            'permissions.destroy',
        ];

        foreach($permissions as $permission){
            Permission::create([
                'name' => $permission
            ]);
        }

        // All Permissions
        $permission_saved = Permission::pluck('id')->toArray();
        
        // Give Role Admin All Access
        $role = Role::whereId(1)->first();
        $role->syncPermissions($permission_saved);
        
        // Admin Role Sync Permission
        $user = User::where('role_id', 1)->first();
        $user->assignRole($role->id);
    }
}
