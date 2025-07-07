<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bid;

class BidSeeder extends Seeder
{
    public function run()
    {
        Bid::create(['title' => 'Office Supplies', 'type' => 'alternative']);
        Bid::create(['title' => 'Equipment Repair', 'type' => 'alternative']);
        Bid::create(['title' => 'Computer Units', 'type' => 'competitive']);
        Bid::create(['title' => 'Internet Service', 'type' => 'competitive']);
    }
}
