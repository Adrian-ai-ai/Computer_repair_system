<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Job; // <-- Make sure this line is here

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['first_name','last_name','phone','email'];

    // Fix the relationship
    public function jobs()
    {
        return $this->hasMany(Job::class, 'client_id'); // <-- client_id must match jobs table
    }
}

