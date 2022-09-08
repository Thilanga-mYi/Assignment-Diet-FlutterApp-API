<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\GoalHasMeals;
use App\Models\GoalNutritions;
use App\Models\mealItems;
use Illuminate\Http\Request;

class GoalHasMealsController extends Controller
{
    public function mealItemEnroll(Request $request)
    {

        error_log(json_encode($request->all()));
        $user_id = $request->user;
        $day = $request->day;
        $meal_time = $request->meal_time;

        $total_carbs = 0.0;
        $total_fat = 0.0;
        $total_protein = 0.0;

        $goal = Goal::where('user_id', $user_id)->where('status', 1)->first();
        $goalNutritions = GoalNutritions::where('user_id', $user_id)->where('goal_id', $goal->id)->first();

        if ($meal_time == 1) {
            $goalNutritions_time_fat = $goalNutritions->breakfast_fat;
            $goalNutritions_time_carbs = $goalNutritions->breakfast_carbs;
            $goalNutritions_time_protein = $goalNutritions->breakfast_protein;
        } else if ($meal_time == 2) {
            $goalNutritions_time_fat = $goalNutritions->lunch_fat;
            $goalNutritions_time_carbs = $goalNutritions->lunch_carbs;
            $goalNutritions_time_protein = $goalNutritions->lunch_protein;
        } else if ($meal_time == 3) {
            $goalNutritions_time_fat = $goalNutritions->dinner_fat;
            $goalNutritions_time_carbs = $goalNutritions->dinner_carbs;
            $goalNutritions_time_protein = $goalNutritions->dinner_protein;
        }

        if ($request->has('save')) {
            GoalHasMeals::where('goal_id', $goal->id)
                ->where('day', $day)
                ->where('meal_time', $meal_time)->delete();
        }

        foreach (json_decode($request->data) as $key => $item) {

            $mealItemObj = mealItems::find($item->id);

            $meal_item_fat = $mealItemObj->fat_level;
            $meal_item_carbs = $mealItemObj->carbs_level;
            $meal_item_protein = $mealItemObj->protein_level;

            $total_fat += $meal_item_fat / 100 * $item->value;
            $total_carbs += $meal_item_carbs / 100 * $item->value;
            $total_protein += $meal_item_protein / 100 * $item->value;

            error_log($total_carbs > $goalNutritions_time_carbs);
            error_log($total_fat > $goalNutritions_time_fat);
            error_log($total_protein > $goalNutritions_time_protein);
            
            error_log($total_carbs);
            error_log($goalNutritions_time_carbs);
            error_log($meal_time);

            if (
                $total_carbs > $goalNutritions_time_carbs ||
                $total_fat > $goalNutritions_time_fat ||
                $total_protein > $goalNutritions_time_protein
            ) {
                return response(
                    [
                        "message" => "Invaild Nutrition Levels. Please Try Again",
                        "fat" => round($total_fat, 1),
                        "carbs" => round($total_carbs, 1),
                        "protein" => round($total_protein, 1),
                    ],
                    400
                );
            }

            if ($request->has('save')) {
                $data = [
                    'user_id' => $user_id,
                    'goal_id' => Goal::where('user_id', $user_id)->where('status', 1)->first()->id,
                    'day' => $day,
                    'meal_time' => $meal_time,
                    'meal_item_id' => $item->id,
                    'value' => $item->value,
                    'status' => 1,
                ];
                GoalHasMeals::create($data);
            }
        }

        return response(
            [
                "message" => "Successfully saved meal plan",
                "fat" => round($total_fat, 1),
                "carbs" => round($total_carbs, 1),
                "protein" => round($total_protein, 1),
            ],
            200
        );
    }
}
