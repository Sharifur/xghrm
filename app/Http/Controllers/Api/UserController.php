<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\UserServices;
use App\Mail\BasicMail;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validate = UserServices::validateLoginRequest($request->all());

        if ($validate->fails()){
            return UserServices::validationErrorsResponse($validate);
        }

        $validated = $validate->validated();

        // Set login type
        $user_login_type = UserServices::loginUserType($validated["username"]);

        if($user_login_type == 'email' && !UserServices::isValideEmail($validated["username"])){
            return UserServices::emailValidationResponse();
        }

        $user = User::select('id', 'password', $user_login_type,'email_verified')->where($user_login_type, $validated["username"])->first();
        if (!$user || !Hash::check($validated["password"], $user->password)) {
            return response()->json([
                'message' => __('Invalid ' . $user_login_type . ' or Password')
            ])->setStatusCode(422);
        }

        $token = $user->createToken(Str::slug(env('APP_NAME', 'xhgrm')) . 'api_keys')->plainTextToken;

        return response()->json([
            'users' => $user,
            'token' => $token,
        ]);
    }


    public function checkUsername(Request $request){
        $validatedData = $request->validate([
            "username" => "required|string"
        ]);

        // todo:: check username length and send response
        if(str($validatedData["username"])->length() < 6){
            return response()->json([
                "type" => "danger",
                "msg" => __("The username must be at least 6 characters.")
            ]);
        }

        // todo:: now check this user is exist on database or not
        $user = User::where($validatedData)->count();

        return response()->json([
            "type" => $user == 0 ? "success" : "danger",
            "msg" => $user == 0 ? __("This username is available") : __("This username is already been taken")
        ]);
    }

    public function changePassword(Request $request){
        $validator = Validator::make($request->all(), [
            "password" => "required|string|min:6|confirmed",
        ]);

        // todo:: check username length and send response
        if($validator->fails()){
            return response()->json([
                "type" => "danger",
                "msg" => $validator->errors()
            ],422);
        }

        //todo: change password
        UserServices::changePassword($validator->validated());

        try{
            $message = 'Hello '.\auth('sanctum')->user()->name.'.<br>';
            $message .= sprintf('Your password has been changed just now. If you haven not changed the password, Contact your Hr-Manager.',ucwords(str_replace(['-','_'],' ',$request->type)));
            \Mail::to(auth('sanctum')->user()->email)->send(new BasicMail([
                'subject' => sprintf('Your password has been changed'),
                'message' => $message,
            ]));
        }catch (\Exception $e){
            \Log::error($e->getMessage());
        }

        return response()->json([
            "type" => "success",
            "msg" =>  __("password changed")
        ]);
    }

    public function changeProfileInfo(Request $request){
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "name" => "required|string|max:191",
            "phone" => "required|numeric",
            "address" => "required|string|max:191",
        ]);

        // todo:: check username length and send response
        if($validator->fails()){
            return response()->json([
                "type" => "danger",
                "msg" => $validator->errors()
            ],422);
        }

        //todo: update profile information
        UserServices::updateProfile($validator->validated());

        return response()->json([
            "type" => "success",
            "msg" =>  __("profile Updated")
        ]);
    }

    public function userInfo(){
        $userInfo = User::find(\auth("sanctum")->id());
        if (is_null($userInfo)){
            return response()->json([
                "type" => "danger",
                "msg" => __("user not found")
            ],422);
        }
        return response()->json([
            "type" => "success",
            "userInfo" => [
                "name" => $userInfo->name,
                "email" => $userInfo->email,
                "id" => $userInfo->id,
                "phone" => $userInfo->phone,
                "address" => $userInfo->address,
                "joinDate" => $userInfo?->employee?->joinDate
            ]
        ]);
    }

    public function leaveList(Request $request){

        $userInfo = User::find(\auth("sanctum")->id());
        $leaveList = AttendanceLog::select(['name','date_time','type','status'])->where(['employee_id' => $userInfo?->employee?->id])->whereIn('type' ,[ 'leave','sick-leave','work-from-home'])->paginate(20)->withQueryString();
        //todo: update profile information

        return response()->json([
            "type" => "success",
            'leaveList' => $leaveList
        ]);
    }


}
