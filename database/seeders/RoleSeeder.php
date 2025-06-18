<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'manager', 'guard_name' => 'web']);
        Role::create(['name' => 'designer', 'guard_name' => 'web']);
        Role::create(['name' => 'developer', 'guard_name' => 'web']);
        Role::create(['name' => 'marketer', 'guard_name' => 'web']);
    }
}