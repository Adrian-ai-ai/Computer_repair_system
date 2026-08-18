<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobAccessory extends Model
{
    protected $fillable = ['job_id','name','description','quantity'];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
