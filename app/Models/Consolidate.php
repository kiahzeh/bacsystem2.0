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
        return $this->belongsToMany(PurchaseRequest::class, 'consolidate_purchase_request', 'consolidate_id', 'pr_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Ensure that the cpr_number is unique
            do {
                $lastCpr = self::orderBy('id', 'desc')->first();
                $nextNumber = $lastCpr ? ((int) filter_var($lastCpr->cpr_number, FILTER_SANITIZE_NUMBER_INT)) + 1 : 1;
                $newCprNumber = 'CPR-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            } while (self::where('cpr_number', $newCprNumber)->exists());  // Check for existing CPR number

            // Assign the unique CPR number
            $model->cpr_number = $newCprNumber;
        });
    }
}




