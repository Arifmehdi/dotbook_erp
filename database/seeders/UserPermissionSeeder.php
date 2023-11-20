<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class UserPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = User::where('username', 'superadmin')->first();

        $permissions = Permission::pluck('name');

        foreach ($permissions as $permissionName) {
            $superAdmin->givePermissionTo($permissionName);
            // echo $permission . PHP_EOL;
        }
    }
}
