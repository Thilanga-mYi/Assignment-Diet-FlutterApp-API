<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\GoalHasMeals;
use App\Models\GoalNutritions;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class GoalController extends Controller
{
    public function enroll(Request $request)
    {
        error_log(json_encode($request->all()));

        $validator = Validator::make($request->all(), [
            'goal_type_id' => 'required',
            'height' => 'nullable',
            'weight' => 'nullable',
            'goal_weight' => 'nullable',
            'body_type' => 'nullable',
            'activity_level' => 'nullable',
            'vegi_type' => 'nullable',
            'drink_water' => 'nullable',
            'diabetes' => 'nullable',
            'cholesterol' => 'nullable',
            'fatty_liver' => 'nullable',
            'user' => 'required',
        ]);

        if ($validator->fails()) {
            error_log(json_encode($validator->errors()->all()));
            return response(['errors' => $validator->errors()->all()], 400);
        }

        $bmr = 0;
        $tdee = 0;
        $tdee = 0;

        $user = User::where('id', $request->user)->first();
        $age = Carbon::parse($user->dob)->diff(Carbon::now())->format('%y');
        $bmr = ((10 * $request->weight) + (6.25 * $request->height)) - ((5 * $age) + (($user->gender == 1) ? 5 : -161));

        error_log($bmr);

        $active_value = 0;
        if ($request->activity_level == 1) {
            $active_value = 1.2;
        } else if ($request->activity_level == 2) {
            $active_value = 1.375;
        } else if ($request->activity_level == 3) {
            $active_value = 1.55;
        } else if ($request->activity_level == 4) {
            $active_value = 1.725;
        } else if ($request->activity_level == 5) {
            $active_value = 1.9;
        }

        if ($request->goal_type_id == 1) {
            if ($request->goal_weight == 1) {
                $tdee = $bmr * $active_value * 110 / 100 * 113 / 100;
            }
            if ($request->goal_weight == 2) {
                $tdee = $bmr * $active_value * 110 / 100 * 125 / 100;
            }
            if ($request->goal_weight == 3) {
                $tdee = $bmr * $active_value * 110 / 100 * 149 / 100;
            }
        }

        if ($request->goal_type_id == 2) {
            if ($request->goal_weight == 1) {
                $tdee = $bmr * $active_value * 110 / 100 * 87 / 100;
            }
            if ($request->goal_weight == 2) {
                $tdee = $bmr * $active_value * 110 / 100 * 75 / 100;
            }
            if ($request->goal_weight == 3) {
                $tdee = $bmr * $active_value * 110 / 100 * 51 / 100;
            }
        }

        if ($request->goal_type_id == 3) {
            $tdee = $bmr * $active_value * 110 / 100;
        }

        if ($request->goal_type_id == 4) {
            $tdee = $bmr * $active_value;
        }

        error_log($tdee);

        // macronutrient according to goal
        $fat = 0;
        $carbs = 0;
        $protien = 0;

        if ($request->goal_type_id == 3) {
            $fat = 30;
            $carbs = 50;
            $protien = 20;
        } else if ($request->goal_type_id == 2) {
            $fat = 25;
            $carbs = 50;
            $protien = 25;
        } else if ($request->goal_type_id == 1) {
            $fat = 20;
            $carbs = 50;
            $protien = 30;
        }

        error_log('FAT CARBS AND PROTEIN ------------------------------------');
        error_log($fat . '/' . $carbs . '/' . $protien);

        $diabetic_level = 0;
        $cholesterol_level = 0;
        $fatty_liver_ALT_level = 0;

        //diabetes level
        // if ($request->diabetes < 100) {
        //     $diabetic_level = 0;
        // } else if ($request->diabetes < 125) {
        //     $diabetic_level = 1;
        // } else {
        //     $diabetic_level = 2;
        // }

        if ($request->diabetes < 100) {
            $diabetic_level = 0;
        } else {
            $diabetic_level = 1;
        }

        //cholesterol level
        // if ($request->cholesterol < 200) {
        //     $cholesterol_level = 0;
        // } else if ($request->cholesterol < 240) {
        //     $cholesterol_level = 1;
        // } else {
        //     $cholesterol_level = 2;
        // }

        if ($request->cholesterol < 200) {
            $cholesterol_level = 0;
        } else {
            $cholesterol_level = 1;
        }

        //Fatty liver Level
        if ($request->fatty_liver < 40) {
            $fatty_liver_ALT_level = 0;
        } else {
            $fatty_liver_ALT_level = 1;
        }

        error_log('DIABETIC CHOLESTEROL AND ATL LEVEL ------------------------------------');
        error_log($diabetic_level . '/' . $cholesterol_level . '/' . $fatty_liver_ALT_level);

        $deseases_status = 0;

        //Having multiple deseases 
        if ($diabetic_level == 0 && $cholesterol_level == 0 && $fatty_liver_ALT_level == 0) {
            $deseases_status = 0;
            $fat = 30;
            $carbs = 50;
            $protien = 20;
        } else if ($diabetic_level == 1 && $cholesterol_level == 1 && $fatty_liver_ALT_level == 0) {
            $deseases_status = 1;
            $fat = 30;
            $carbs = 50;
            $protien = 20;
        } else if ($diabetic_level == 1 && $cholesterol_level == 0 && $fatty_liver_ALT_level == 1) {
            $deseases_status = 2;
            $fat = 30;
            $carbs = 50;
            $protien = 20;
        } else if ($diabetic_level == 0 && $cholesterol_level == 1 && $fatty_liver_ALT_level == 1) {
            $deseases_status = 3;
            $fat = 25;
            $carbs = 55;
            $protien = 20;
        } else if ($diabetic_level == 1 && $cholesterol_level == 1 && $fatty_liver_ALT_level == 1) {
            $deseases_status = 4;
            $fat = 25;
            $carbs = 55;
            $protien = 20;
        } else if ($diabetic_level == 1 && $cholesterol_level == 0 && $fatty_liver_ALT_level == 0) {
            $deseases_status = 5;
            $fat = 25;
            $carbs = 55;
            $protien = 20;
        } else if ($diabetic_level == 0 && $cholesterol_level == 1 && $fatty_liver_ALT_level == 0) {
            $deseases_status = 6;
            $fat = 25;
            $carbs = 55;
            $protien = 20;
        } else if ($diabetic_level == 0 && $cholesterol_level == 0 && $fatty_liver_ALT_level == 1) {
            $deseases_status = 7;
            $fat = 25;
            $carbs = 55;
            $protien = 20;
        }

        error_log("Deseases level:" . $deseases_status);
        error_log("macronutrients levels: Fat:" . $fat . "%, Carbs: " . $carbs . "%, Protein: " . $protien . "%");

        //TDEE in grams 
        $fat_grams = 0.0;
        $cards_grams = 0.0;
        $protein_grams = 0.0;

        $fat_grams = ($tdee * $fat / 100) / 9;
        $cards_grams = ($tdee * $carbs / 100) / 4;
        $protein_grams = ($tdee * $protien / 100) / 4;

        error_log("macronutrients grams levels: Fat:" . $fat_grams . "g, Carbs: " . $cards_grams . "g, Protein: " . $protein_grams . "g");

        //According to the meal /breakfast /lunch /Dinner
        $breakfast_fat_grams = 0.0;
        $breakfast_carbs_grams = 0.0;
        $breakfast_protein_grams = 0.0;

        $lunch_fat_grams = 0.0;
        $lunch_carbs_grams = 0.0;
        $lunch_protein_grams = 0.0;

        $dinner_fat_grams = 0.0;
        $dinner_carbs_grams = 0.0;
        $dinner_protein_grams = 0.0;

        if ($request->goal_type_id == 3) {
            $breakfast_fat_grams = $fat_grams * 35 / 100;
            $breakfast_carbs_grams = $cards_grams * 35 / 100;
            $breakfast_protein_grams = $protein_grams * 35 / 100;

            $lunch_fat_grams = $fat_grams * 40 / 100;
            $lunch_carbs_grams = $cards_grams * 40 / 100;
            $lunch_protein_grams = $protein_grams * 40 / 100;

            $dinner_fat_grams = $fat_grams * 25 / 100;
            $dinner_carbs_grams = $cards_grams * 25 / 100;
            $dinner_protein_grams = $protein_grams * 25 / 100;
        } else if ($request->goal_type_id == 2) {
            $breakfast_fat_grams = $fat_grams * 35 / 100;
            $breakfast_carbs_grams = $cards_grams * 35 / 100;
            $breakfast_protein_grams = $protein_grams * 35 / 100;

            $lunch_fat_grams = $fat_grams * 40 / 100;
            $lunch_carbs_grams = $cards_grams * 40 / 100;
            $lunch_protein_grams = $protein_grams * 40 / 100;

            $dinner_fat_grams = $fat_grams * 25 / 100;
            $dinner_carbs_grams = $cards_grams * 25 / 100;
            $dinner_protein_grams = $protein_grams * 25 / 100;
        } else if ($request->goal_type_id == 1) {
            $breakfast_fat_grams = $fat_grams * 30 / 100;
            $breakfast_carbs_grams = $cards_grams * 30 / 100;
            $breakfast_protein_grams = $protein_grams * 30 / 100;

            $lunch_fat_grams = $fat_grams * 40 / 100;
            $lunch_carbs_grams = $cards_grams * 40 / 100;
            $lunch_protein_grams = $protein_grams * 40 / 100;

            $dinner_fat_grams = $fat_grams * 30 / 100;
            $dinner_carbs_grams = $cards_grams * 30 / 100;
            $dinner_protein_grams = $protein_grams * 30 / 100;
        } else if ($request->goal_type_id == 4) {
            $breakfast_fat_grams = $fat_grams * 35 / 100;
            $breakfast_carbs_grams = $cards_grams * 35 / 100;
            $breakfast_protein_grams = $protein_grams * 35 / 100;

            $lunch_fat_grams = $fat_grams * 40 / 100;
            $lunch_carbs_grams = $cards_grams * 40 / 100;
            $lunch_protein_grams = $protein_grams * 40 / 100;

            $dinner_fat_grams = $fat_grams * 25 / 100;
            $dinner_carbs_grams = $cards_grams * 25 / 100;
            $dinner_protein_grams = $protein_grams * 25 / 100;
        }

        error_log("Fat grams per day: Breakfast: " . $breakfast_fat_grams . "g, Lunch: " . $lunch_fat_grams . "g, Dinner: " . $dinner_fat_grams . "g");
        error_log("Carbs grams per day: Breakfast: " . $breakfast_carbs_grams . "g, Lunch: " . $lunch_carbs_grams . "g, Dinner: " . $dinner_carbs_grams . "g");
        error_log("Protein grams per day: Breakfast: " . $breakfast_protein_grams . "g, Lunch: " . $lunch_protein_grams . "g, Dinner: " . $dinner_protein_grams . "g");

        $data = [
            'user_id' => $request->user,
            'goal_type_id' => $request->goal_type_id,
            'vegi_type' => $request->vegi_type,
            'drink_water' => $request->drink_water,
            'height' => $request->height,
            'weight' => $request->weight,
            'goal_weight' => $request->goal_weight,
            'body_type' => $request->body_type,
            'activity_level' => $request->activity_level,
            'bmr' => $bmr,
            'tdee' => $tdee,
            'status' => 1,
        ];

        if ($request->goal_type_id == 4) {
            $data['diabetes'] = $request->diabetes;
            $data['cholesterol'] = $request->cholesterol;
            $data['fatty_liver'] = $request->fatty_liver;
        }

        $currentGoal = Goal::where('user_id', $request->user)->get();
        foreach ($currentGoal as $key => $goal_obj) {
            Goal::where('id', $goal_obj->id)->update(['status' => 4]);
        }

        $saved = Goal::create($data);
        Goal::where('id', $saved->id)->update(['ref' => 'GTRN' . str_pad($saved->id, 4, '0', STR_PAD_LEFT)]);
        $payment_records = Payment::where('goal', $saved->id)->where('status', '!=', 3)->get();

        $nutrition_data = [
            'user_id' => $saved->user_id,
            'goal_id' => $saved->id,
            'breakfast_fat' => round($breakfast_fat_grams, 0),
            'breakfast_carbs' => round($breakfast_carbs_grams, 0),
            'breakfast_protein' => round($breakfast_protein_grams, 0),
            'lunch_fat' => round($lunch_fat_grams, 0),
            'lunch_carbs' => round($lunch_carbs_grams, 0),
            'lunch_protein' => round($lunch_protein_grams, 0),
            'dinner_fat' => round($dinner_fat_grams, 0),
            'dinner_carbs' => round($dinner_carbs_grams, 0),
            'dinner_protein' => round($dinner_protein_grams, 0),
            'status' => 1,
        ];

        GoalNutritions::create($nutrition_data);

        return response(
            [
                "message" => "Successfully saved goal",
                'bmr' => number_format($saved->bmr * 7, 2),
                'tdee' => number_format($saved->tdee, 2),
                'tdee_week' => number_format($saved->tdee * 7, 2),
                'drinking_water' => number_format($saved->drink_water, 2),
                'gender' => User::where('id', $saved->user_id)->first()->gender,
                'payment_status' => count($payment_records),
            ],
            200
        );
    }

    public function getGoalDetails(Request $request)
    {
        if ($goal = Goal::where('user_id', $request->user)->where('status', 1)->first()) {

            $payment_records = Payment::where('goal', $goal->id)->where('status', '!=', 3)->get();

            return response(
                [
                    "message" => "",
                    'bmr' => number_format($goal->bmr * 7, 2),
                    'tdee' => number_format($goal->tdee, 2),
                    'tdee_week' => number_format($goal->tdee * 7, 2),
                    'drinking_water' => number_format($goal->drink_water, 2),
                    'gender' => User::where('id', $goal->user_id)->first()->gender,
                    'payment_status' => count($payment_records),
                ],
                200
            );
        } else {
            return response(
                [],
                400
            );
        }
    }

    public function getMealValues(Request $request)
    {
        $fat = 0;
        $carbs = 0;
        $protein = 0;

        $nutitionValues = GoalNutritions::where('goal_id', Goal::where('user_id', $request->user)->where('status', 1)->first()->id)->first();

        if ($request->meal_time == 1) {
            $fat = $nutitionValues->breakfast_fat;
            $carbs = $nutitionValues->breakfast_carbs;
            $protein = $nutitionValues->breakfast_protein;
        } else if ($request->meal_time == 2) {
            $fat = $nutitionValues->lunch_fat;
            $carbs = $nutitionValues->lunch_carbs;
            $protein = $nutitionValues->lunch_protein;
        } else if ($request->meal_time == 3) {
            $fat = $nutitionValues->dinner_fat;
            $carbs = $nutitionValues->dinner_carbs;
            $protein = $nutitionValues->dinner_protein;
        }

        $meal_values = [
            'added_fat' => 0.0,
            'added_carbs' => 0.0,
            'added_protein' => 0.0,
            'recommended_fat' => $fat,
            'recommended_carbs' => $carbs,
            'recommended_protein' => $protein,
        ];

        return $meal_values;
    }

    public function payment(Request $request)
    {
        error_log(json_encode($request->all()));

        $validator = Validator::make($request->all(), [
            'user' => 'required',
            'ref' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 400);
        }

        $goal = Goal::where('user_id', $request->user)->where('status', 1)->first();
        Payment::create([
            'ref' => $request->ref,
            'date' => Carbon::now(),
            'goal' => $goal->id,
            'user' => $request->user,
            'status' => 2,
        ]);

        return response([
            [
                "message" => "Successfully Saved Payment"
            ],
            200
        ]);
    }

    public function goalHistory(Request $request)
    {
        error_log(json_encode($request->all()));
        $data = [];
        $records = Goal::where('user_id', $request->user)->where('status', 4)->get();

        foreach ($records as $key => $goal) {
            $data[] = [
                'goal_type_id' => $goal->goal_type_id,
                'body_type' => $goal->body_type,
                'weight' => $goal->weight,
                'goal_weight' => $goal->goal_weight,
            ];
        }

        return ['goal_list' => $data];
    }
}
