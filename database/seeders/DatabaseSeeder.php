<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = json_decode(file_get_contents(database_path('seeders/data/roles.json')), true);
        foreach ($roles as $role) {
            $roleObj = Role::create([
                'name' => $role,
            ]);
        }

        // default permissions
        $permissions = json_decode(file_get_contents(database_path('seeders/data/permissions.json')), true);
        foreach ($permissions as $permission) {
            $perm = Permission::create([
                'name' => $permission['name'],
                'module_name' => $permission['module_name'],
                'default_permission' => $permission['default_permission'],
            ]);
            foreach ($permission['roles'] as $role) {
                $perm->assignRole($role);
            }

        }

        $users = json_decode(file_get_contents(database_path('seeders/data/users.json')), true);
        foreach ($users as $user) {
            $userObj = User::create([
                'uuid' => \Illuminate\Support\Str::uuid(),
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => bcrypt($user['password']),
            ]);
            foreach ($user['roles'] as $role) {
                $userObj->assignRole($role);
            }

        }

    }
}
