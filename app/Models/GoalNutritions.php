<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoalNutritions extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'goal_id',
        'breakfast_fat',
        'breakfast_carbs',
        'breakfast_protein',
        'lunch_fat',
        'lunch_carbs',
        'lunch_protein',
        'dinner_fat',
        'dinner_carbs',
        'dinner_protein',
        'status',
    ];
}
