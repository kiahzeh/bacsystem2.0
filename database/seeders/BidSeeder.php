<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bid;

class BidSeeder extends Seeder
{
    public function run()
    {
        // Create sample bids with the correct schema fields
        Bid::create([
            'purchase_request_id' => 1,
            'vendor_name' => 'Office Supplies Vendor',
            'amount' => 5000.00
        ]);
        
        Bid::create([
            'purchase_request_id' => 1,
            'vendor_name' => 'Equipment Repair Vendor',
            'amount' => 3500.00
        ]);
        
        Bid::create([
            'purchase_request_id' => 2,
            'vendor_name' => 'Computer Units Vendor',
            'amount' => 25000.00
        ]);
        
        Bid::create([
            'purchase_request_id' => 2,
            'vendor_name' => 'Internet Service Provider',
            'amount' => 12000.00
        ]);
    }
}
