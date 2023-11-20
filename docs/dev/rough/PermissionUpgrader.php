<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo 'Development'.PHP_EOL;
echo '=============================='.PHP_EOL;

$checkPermissionForUser_ById = 8;

$role_permissions = DB::table('role_permissions')->where('id', $checkPermissionForUser_ById)->first();
$vars = get_object_vars($role_permissions);
$modules = array_keys($vars);
$modules = array_slice($modules, 2);

Schema::disableForeignKeyConstraints();
\DB::table('permissions')->truncate();

$permission_global_array = [];
foreach ($modules as $key => $module) {
    $json = $vars[$module];
    $dataObj = json_decode($json);
    if (is_object($dataObj)) {
        $dataArray = get_object_vars($dataObj);
        array_push($permission_global_array, $dataArray);
    }
}

$final = [];
$it = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($permission_global_array));
foreach ($it as $key => $i) {
    array_push($final, $key);
}

$final = array_unique($final);
foreach ($final as $key => $name) {
    \DB::table('permissions')->insert(['name' => $name, 'guard_name' => 'web']);
}
echo 'Finished';
