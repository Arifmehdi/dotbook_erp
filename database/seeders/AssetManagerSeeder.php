<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AssetManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (! Role::where('name', 'asset manager')->exists()) {
            echo 'Creating role and giving permissions....';
            $assetMangerRole = Role::create(['name' => 'asset manager']);
            $permissions = Permission::where('name', 'LIKE', 'asset%')->pluck('name')->toArray();
            $assetMangerRole->syncPermissions($permissions);

            echo ' DONE!';
        }

    }
}
