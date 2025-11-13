<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PurchaseRequest;
use App\Models\User;
use App\Models\Department;

class PurchaseRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'ATP',
            'Procurement',
            'Posting in PhilGEPS',
            'Pre-Bid',
            'Bid Opening',
            'Bid Evaluation',
            'Post Qualification',
            'Confirmation on Approval',
            'Issuance of Notice of Award',
            'Purchase Order',
            'Contract',
            'Notice to Proceed',
            'Posting of Purchase or PhilGEPS',
            'Forward Supply'
        ];

        $procurementModes = ['Alternative', 'Competitive', 'Others'];
        $categories = ['Goods', 'Infrastructure', 'Services'];
        $fundings = ['GAA', 'LGU', 'Others'];

        // Create 3 purchase requests for each department
        $departments = Department::all();
        foreach ($departments as $department) {
            // Get a user from this department
            $user = User::where('department_id', $department->id)->first();

            // If no user exists for this department, skip
            if (!$user)
                continue;

            for ($i = 1; $i <= 3; $i++) {
                $prNumber = 'PR-' . $department->id . '-' . str_pad($i, 3, '0', STR_PAD_LEFT) . '-' . date('Y');
                $mode = $procurementModes[array_rand($procurementModes)];
                $category = $categories[array_rand($categories)];
                $funding = $fundings[array_rand($fundings)];
                
                // Normalize "Others" to custom values to ensure dashboard counts
                $modeNormalized = $mode === 'Others' ? 'Others' : $mode;
                $categoryNormalized = $category;
                $fundingNormalized = $funding === 'Others' ? 'Special Grant' : $funding;

                PurchaseRequest::updateOrCreate(
                    ['pr_number' => $prNumber],
                    [
                        'name' => $department->name . ' Purchase Request ' . $i,
                        'project_title' => $department->name . ' Project ' . $i,
                        'order_date' => now()->subDays(rand(1, 30)),
                        'department_id' => $department->id,
                        'user_id' => $user->id,
                        'status' => $statuses[array_rand($statuses)],
                        'mode_of_procurement' => $modeNormalized,
                        'abc_approved_budget' => rand(5000, 50000),
                        'category' => $categoryNormalized,
                        'purpose_description' => 'Purpose description for ' . $department->name . ' PR ' . $i,
                        'funding' => $fundingNormalized,
                        'remarks' => 'Sample purchase request for ' . $department->name,
                    ]
                );
            }
        }
    }
}
