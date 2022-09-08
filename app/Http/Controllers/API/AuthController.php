<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {

            $user['goal'] = Goal::where('user_id', $user->id)->where('status', 1)->first();

            error_log(json_encode($user));

            if (Hash::check($request->password, $user->password)) {
                return response($user, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 400);
            }
        } else {
            $response = ["message" => 'User does not exist'];
            return response($response, 400);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'gender' => 'required|numeric|min:1|max:2',
            'dob' => 'required|string',
            'mobile_number' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 400);
        }
        User::create([
            'name' => $request->name,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'mobile_number' => $request->mobile_number,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return response(["message" => "Successfully registered"], 200);
    }
}
