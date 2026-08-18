<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPartsUsed extends Model
{
    protected $fillable = ['job_id','product_id','quantity_used'];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
