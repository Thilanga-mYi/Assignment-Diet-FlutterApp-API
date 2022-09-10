<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\GoalHasMeals;
use App\Models\mealItems;
use App\Models\User;
use Illuminate\Http\Request;

class MealItemsController extends Controller
{
    public function list(Request $request)
    {
        error_log(json_encode($request->all()));
        $data = [];
        $user_vegi_type = Goal::where('user_id', $request->user)
            ->where('status', 1)
            ->first();

        error_log($user_vegi_type);
        error_log('----------------------------------------');
        error_log($user_vegi_type->vegi_type);

        $records =   $user_vegi_type->vegi_type != 5 ?
            (new mealItems)->where('veg_type', $user_vegi_type->vegi_type)->orderby('name', 'ASC')->get() : 
            (new mealItems)->orderby('name', 'ASC')->get();
        // $records =  (new mealItems)->where('veg_type', $user_vegi_type->vegi_type)->orderby('name', 'ASC')->get();


        foreach ($records as $key => $item) {

            $val = GoalHasMeals::where(
                'goal_id',
                Goal::where('user_id', $request->user)->where('status', 1)->first()->id
            )
                ->where('meal_item_id', $item->id)
                ->where('meal_time', $request->meal_time)
                ->where('day', $request->day)
                ->first();

            $data[] = [
                'id' => $item->id,
                'name' => $item->name,
                'protein' => $item->protein_level,
                'carbs' => $item->carbs_level,
                'fat' => $item->fat_level,
                'gl_index' => $item->gl_index,
                'value' => ($val) ? $val->value : ''
            ];
        }

        return ['meal_list' => $data];
    }
}
