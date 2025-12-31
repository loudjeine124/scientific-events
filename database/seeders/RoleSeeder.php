<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'committee']);
        Role::firstOrCreate(['name' => 'participant']);
        Role::firstOrCreate(['name' => 'organizer']);
        Role::firstOrCreate(['name' => 'reviewer']);

    }
}