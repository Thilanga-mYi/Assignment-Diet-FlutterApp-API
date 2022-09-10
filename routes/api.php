<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\GoalController;
use App\Http\Controllers\GoalHasMealsController;
use App\Http\Controllers\MealItemsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/enroll',[GoalController::class,'enroll']);
Route::post('/history',[GoalController::class,'goalHistory']);
Route::post('/get_goal_details',[GoalController::class,'getGoalDetails']);
Route::post('/enroll_payment',[GoalController::class,'payment']);

Route::post('/mealitem/list',[MealItemsController::class,'list']);
Route::post('/mealitem/enroll',[GoalHasMealsController::class,'mealItemEnroll']);
Route::post('/goal/getMealValues',[GoalController::class,'getMealValues']);
Route::post('/goal/enroll_item_to_meal',[GoalController::class,'enrollItemToMeal']);
Route::post('/goal/enroll_meal',[GoalController::class,'enrollMeal']);