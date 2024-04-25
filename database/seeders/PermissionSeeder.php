<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // user
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'read user']);
        Permission::create(['name' => 'update user']);
        Permission::create(['name' => 'delete user']);
        //Client
        Permission::create(['name' => 'create client']);
        Permission::create(['name' => 'read client']);
        Permission::create(['name' => 'update client']);
        Permission::create(['name' => 'delete client']);
        //project
        Permission::create(['name' => 'create project']);
        Permission::create(['name' => 'read project']);
        Permission::create(['name' => 'update project']);
        Permission::create(['name' => 'delete project']);
        //task
        Permission::create(['name' => 'create task']);
        Permission::create(['name' => 'read task']);
        Permission::create(['name' => 'update task']);
        Permission::create(['name' => 'delete task']);
    }
}
