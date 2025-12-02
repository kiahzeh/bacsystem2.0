<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    // Ensure required tables exist; otherwise skip to avoid fatal errors
    if (!Schema::hasTable('users') || !Schema::hasTable('departments')) {
        echo "[UserSeeder] required tables missing (users or departments); skipping seeding.\n";
        return;
    }

    $departments = Department::pluck('id')->toArray();
    if (count($departments) === 0) {
        echo "[UserSeeder] no departments found; skipping user generation.\n";
        return;
    }

    for ($i = 1; $i <= 10; $i++) {
        $values = [
            'name' => 'User ' . $i,
            'password' => Hash::make('password'),
            'department_id' => $departments[array_rand($departments)],
        ];
        if (Schema::hasColumn('users', 'role')) {
            $values['role'] = 'user';
        }
        if (Schema::hasColumn('users', 'is_admin')) {
            $values['is_admin'] = false;
        }
        User::updateOrCreate(
            ['email' => 'user' . $i . '@example.com'],
            $values
        );
    }

    $namedDepartments = Department::where('name', '!=', 'Admin')->get();

    foreach ($namedDepartments as $department) {
        $values = [
            'name' => $department->name . ' User',
            'password' => Hash::make('password'),
            'department_id' => $department->id,
        ];
        if (Schema::hasColumn('users', 'role')) {
            $values['role'] = 'user';
        }
        if (Schema::hasColumn('users', 'is_admin')) {
            $values['is_admin'] = false;
        }
        User::updateOrCreate(
            ['email' => strtolower($department->name) . '@example.com'],
            $values
        );
    }

    // ✅ Admin user from env, prefer Admin department
    $adminEmail = env('ADMIN_EMAIL', 'admin@example.com');
    $adminPassword = env('ADMIN_PASSWORD', 'password');
    $adminName = env('ADMIN_NAME', 'Admin User');

    $adminDeptId = Department::where('name', 'Admin')->value('id') ?? ($departments[0] ?? null);

    $adminValues = [
        'name' => $adminName,
        'password' => Hash::make($adminPassword),
        'department_id' => $adminDeptId ?? ($namedDepartments->random()->id ?? $departments[0]),
        'email_verified_at' => now(),
    ];
    if (Schema::hasColumn('users', 'role')) {
        $adminValues['role'] = 'admin';
    }
    if (Schema::hasColumn('users', 'is_admin')) {
        $adminValues['is_admin'] = true;
    }
    if (Schema::hasColumn('users', 'is_approved')) {
        $adminValues['is_approved'] = true;
    }
    User::updateOrCreate(
        ['email' => $adminEmail],
        $adminValues
    );
}

}
