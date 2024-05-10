<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'create user',
            'read user',
            'update user',
            'delete user',
            'create client',
            'read client',
            'update client',
            'delete client',
            'create project',
            'read project',
            'update project',
            'delete project',
            'create task',
            'read task',
            'update task',
            'delete task',
        ];

        $AdminRole = Role::where('name', 'admin')->first();
        $AdminRole->givePermissionTo($permissions);
    }
}
