<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobStatusHistory extends Model
{
    protected $fillable = ['job_id','status','changed_by','changed_at'];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    protected $table = 'job_status_history';

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
      public $timestamps = false;
}

