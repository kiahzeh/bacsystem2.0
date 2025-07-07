<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsolidatedRequest extends Model
{
    protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        $latestId = self::max('id') ?? 0;
        $model->cpr_number = 'CPR-' . str_pad($latestId + 1, 5, '0', STR_PAD_LEFT);
    });
    
}

public function purchaseRequests()
{
    return $this->belongsToMany(PurchaseRequest::class);
}

}
