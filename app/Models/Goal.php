<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'ref',
        'user_id',
        'goal_type_id',
        'height',
        'weight',
        'goal_weight',
        'body_type',
        'activity_level',
        'vegi_type',
        'drink_water',
        'diabetes',
        'cholesterol',
        'fatty_liver',
        'bmr',
        'tdee',
        'payment_status',
        'status',
    ];

}
