<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consolidate extends Model
{
    use HasFactory;

    protected $fillable = ['cpr_number', 'pr_numbers', 'created_by'];

    protected $casts = [
        'pr_numbers' => 'array',  // Cast the 'pr_numbers' as an array
    ];

    // Define the correct many-to-many relationship for PurchaseRequest
    public function purchaseRequests()
    {
        return $this->belongsToMany(PurchaseRequest::class, 'consolidate_purchase_request', 'consolidate_id', 'purchase_request_id');
    }

    protected static function boot()
    {
        parent::boot();

        // Removed auto-generation logic to allow manual CPR number input
        // The cpr_number will now be set manually by the admin
    }
}




