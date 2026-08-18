<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\JobAccessory;
use App\Models\JobStatusHistory;
use App\Models\Product;
use App\Models\User;

class Job extends Model
{
    protected $fillable = ['job_number','client_id','device_type','brand','model','serial_number','fault_description','status','received_by','received_at','warranty_status','warranty_expiry_date','purchase_date','technician_id'];

    protected $casts = [
        'received_at' => 'datetime',
        'warranty_expiry_date' => 'datetime',
        'purchase_date' => 'datetime',
    ];

    public const STATUS_FLOW = [
        'Received',
        'Diagnosing',
        'Waiting for parts',
        'Repairing',
        'Ready for pickup',
        'Delivered',
    ];

     public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    public function accessories()
    {
        return $this->hasMany(JobAccessory::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(JobStatusHistory::class);
    }

    public function partsUsed()
    {
        return $this->belongsToMany(Product::class, 'job_parts_used')->withPivot('quantity_used', 'is_warranty')->withTimestamps();
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'job_number', 'job_number');
    }
     public function canMoveTo(string $newStatus): bool
    {
        $currentIndex = array_search($this->status, self::STATUS_FLOW);
        $newIndex = array_search($newStatus, self::STATUS_FLOW);

        return $newIndex === $currentIndex + 1;
    }
    public function calculateWarranty()
{
    if (!$this->purchase_date || !$this->warranty_expiry_date) {
        return false;
    }

    return now()->lte($this->warranty_expiry_date);
}

}
