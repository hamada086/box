<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $admin = TeamMember::create([
            'name' => 'مدير النظام',
            'email' => 'admin@boxpixel.com',
            'password' => Hash::make('password'),
            'department' => 'management',
            'position' => 'مدير النظام'
        ]);

        $admin->assignRole('admin');
    }
}