<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_number',
        'client_id',
        'subtotal',
        'tax',
        'discount',
        'total_amount',
        'status',
        'valid_until',
        'created_by',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'valid_until' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_number', 'job_number');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'sent' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Sent</span>',
            'accepted' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Accepted</span>',
            'rejected' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>',
        ];

        return $badges[$this->status] ?? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Unknown</span>';
    }

    public function isExpired()
    {
        return $this->valid_until->isPast();
    }

    public function getDaysUntilExpiryAttribute()
    {
        return now()->diffInDays($this->valid_until, false);
    }
}
