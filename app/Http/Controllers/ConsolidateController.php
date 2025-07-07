<?php

namespace App\Http\Controllers;

use App\Models\Consolidate;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;

class ConsolidateController extends Controller
{
    public function store(Request $request)
    {
        // Get the selected PR numbers from the request
        $prNumbers = $request->input('pr_numbers'); // Array of selected PR IDs

        if (empty($prNumbers)) {
            return back()->withErrors(['error' => 'Please select at least one PR number to consolidate.']);
        }

        // Create a new Consolidate record
        $consolidate = new Consolidate();
        $consolidate->cpr_number = 'CPR-' . strtoupper(uniqid()); // Generate a unique CPR number
        $consolidate->created_by = auth()->user()->id;  // Assuming you're storing the creator's user ID
        $consolidate->save();

        // Attach the selected PRs to this Consolidate record
        foreach ($prNumbers as $prId) {
            $pr = PurchaseRequest::find($prId);
            if ($pr) {
                $consolidate->purchaseRequests()->attach($pr); // Attach PR to CPR (via pivot table)
            }
        }

        return redirect()->route('dashboard')->with('success', 'CPR created successfully.');
    }

    public function showConsolidated()
    {
        // Fetch all consolidated PRs along with their related Purchase Requests (PRs)
        $consolidatedPRs = Consolidate::with('purchaseRequests')->get();
    
        // Pass the data to the view
        return view('consolidation.index', compact('consolidatedPRs'));
    }
}

