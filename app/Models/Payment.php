<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    use HasFactory;
    
    protected $fillable = ['ref', 'date', 'goal', 'user', 'status'];

    public function goaldata()
    {
        return $this->hasOne(Goal::class, 'id', 'goal');
    }

    public function userdata()
    {
        return $this->hasOne(User::class, 'id', 'user');
    }
}
