<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    $this->call([
        DepartmentSeeder::class,
        //UserSeeder::class,//
        PurchaseRequestSeeder::class,
        BidSeeder::class,
    ]);
}
}
