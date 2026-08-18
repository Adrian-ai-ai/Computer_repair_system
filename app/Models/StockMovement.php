<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;

class StockMovement extends Model
{
    protected $fillable = ['product_id', 'movement_type', 'quantity', 'reference_type', 'reference_id', 'created_by'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

