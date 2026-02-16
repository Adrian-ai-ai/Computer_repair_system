<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StockMovement;
use App\Models\Job;

class Product extends Model
{
    protected $fillable = ['name', 'category', 'description', 'minimum_stock', 'warranty_period_months'];

    // One product can have many stock movements
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // One product can be used in many repair jobs
    public function jobsUsedIn()
    {
        return $this->belongsToMany(Job::class, 'job_parts_used')->withPivot('quantity_used')->withTimestamps();
    }

    // Accessor for current stock
    public function getCurrentStockAttribute()
    {
        $stockIn = $this->stockMovements()->where('movement_type', 'IN')->sum('quantity');
        $stockOut = $this->stockMovements()->where('movement_type', 'OUT')->sum('quantity');
        return $stockIn - $stockOut;
    }

    // Accessor for low stock check
    public function getIsLowAttribute()
    {
        return $this->current_stock <= $this->minimum_stock;
    }
}
