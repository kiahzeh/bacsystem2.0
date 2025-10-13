<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bid;
use App\Models\PurchaseRequest;

class BidSeeder extends Seeder
{
    public function run()
    {
        // Reference existing purchase requests dynamically to avoid foreign key issues
        $pr1 = PurchaseRequest::orderBy('id')->first();
        $pr2 = PurchaseRequest::orderBy('id')->skip(1)->first();

        if (!$pr1) {
            // No purchase requests present; skip seeding bids
            return;
        }

        Bid::updateOrCreate(
            [
                'purchase_request_id' => $pr1->id,
                'vendor_name' => 'Office Supplies Vendor',
            ],
            [
                'amount' => 5000.00,
            ]
        );

        Bid::updateOrCreate(
            [
                'purchase_request_id' => $pr1->id,
                'vendor_name' => 'Equipment Repair Vendor',
            ],
            [
                'amount' => 3500.00,
            ]
        );

        if ($pr2) {
            Bid::updateOrCreate(
                [
                    'purchase_request_id' => $pr2->id,
                    'vendor_name' => 'Computer Units Vendor',
                ],
                [
                    'amount' => 25000.00,
                ]
            );

            Bid::updateOrCreate(
                [
                    'purchase_request_id' => $pr2->id,
                    'vendor_name' => 'Internet Service Provider',
                ],
                [
                    'amount' => 12000.00,
                ]
            );
        }
    }
}
