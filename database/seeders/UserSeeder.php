<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $departments = Department::pluck('id')->toArray();

    for ($i = 1; $i <= 10; $i++) {
        User::create([
            'name' => 'User ' . $i,
            'email' => 'user' . $i . '@example.com',
            'password' => Hash::make('password'),
            'department_id' => $departments[array_rand($departments)],
        ]);
    }

    $namedDepartments = Department::where('name', '!=', 'Admin')->get();

    foreach ($namedDepartments as $department) {
        User::create([
            'name' => $department->name . ' User',
            'email' => strtolower($department->name) . '@example.com',
            'password' => Hash::make('password'),
            'department_id' => $department->id,
            'role' => 'user',
        ]);
    }

    // ✅ Admin user
    User::updateOrCreate(
        ['email' => 'admin@example.com'],
        [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
            'department_id' => $namedDepartments->random()->id ?? $departments[0],
            'role' => 'admin',
            'email_verified_at' => now(),
        ]
    );
}

}
