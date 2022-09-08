<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoalHasMeals extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'goal_id',
        'day',
        'meal_time',
        'meal_item_id',
        'value',
        'status',
    ];
}
