<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Department;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Designation;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Designation::create(['id'=> Str::uuid(),'name' => 'admin']);
        Department::create(['id'=> Str::uuid(),'name' => 'admin']);
        $this->call([
            CountriesTableSeeder::class,
            StatesTableSeeder::class,
            CitiesTableChunkOneSeeder::class,
            CitiesTableChunkTwoSeeder::class,
            CitiesTableChunkThreeSeeder::class,
            CitiesTableChunkFourSeeder::class,
            CitiesTableChunkFiveSeeder::class,
        ]);
        foreach (Role::roles as $key => $role) {
            Role::create(['name' => $key]);
        }
        $admin = User::create([
            'name' => 'Admin',
            'avtar' => 'admin.png',
            'email' => 'netAdmin@test.com',
            'password' => Hash::make('password'),
            'phone_no' => '7046260656',
            'designation_id' => null,
            'department_id' => null,
            'date_of_birth' => Carbon::parse('2000-01-01'),
            'gender' => 'female',
            'date_of_join' => Carbon::parse('01-04-2023'),
            'address' => 'surat',
            'about' => null
        ]);

        $admin->assignRole('super-admin');
    }
}
