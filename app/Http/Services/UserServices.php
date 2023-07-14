<?php

namespace App\Http\Services;


use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserServices
{
    public static function validateLoginRequest(?array $request){
        return Validator::make($request,[
            'username' => 'required|max:191',
            'password' => 'required',
        ]);
    }

    public static function validateRegisterRequest(?array $request){
        return Validator::make($request,[
            'full_name' => 'required|max:191',
            'email' => 'required|email|unique:users|max:191',
            'username' => 'required|unique:users|max:191',
            'phone' => 'required|unique:users|max:191',
            'password' => 'required|min:6|max:191',
            'country_id' => 'required',
            'state_id' => 'nullable',
            'terms_conditions' => 'required',
        ]);
    }

    public static function createNewUser(array $validatedData){
        return User::create([
            'name' => $validatedData["full_name"],
            'email' => $validatedData["email"],
            'username' => $validatedData["username"],
            'phone' => $validatedData["phone"],
            'password' => Hash::make($validatedData["password"]),
            'country' => $validatedData["country_id"],
            'state' => $validatedData["state_id"],
        ]);
    }
    public static function changePassword(array $validatedData){
        return User::find(auth("sanctum")->id())->update([
            'password' => Hash::make($validatedData["password"]),
        ]);
    }
    public static function validationErrorsResponse($validate): JsonResponse
    {
        return response()->json([
            'validation_errors' => $validate->messages()
        ])->setStatusCode(422);
    }

    public static function loginUserType(string $username): string
    {
        return filter_var($username,FILTER_VALIDATE_EMAIL) ? "email" : "username";
    }

    public static function isValideEmail($email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    public static function emailValidationResponse(): JsonResponse
    {
        return response()->json([
            'message' => __('invalid Email'),
        ])->setStatusCode(422);
    }

    public static function updateProfile(array $validatedData){
        return User::find(auth("sanctum")->id())->update([
            'email' => $validatedData["email"],
            'name' => $validatedData["name"],
            'phone' => $validatedData["phone"],
            'address' => $validatedData["address"],
        ]);
    }
}
