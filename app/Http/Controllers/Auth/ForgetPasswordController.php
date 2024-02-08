<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Helpers\ApiResponseHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CheckOtpRequest;
use Illuminate\Support\Facades\Validator;
use MFrouh\Sms4jawaly\Facades\Sms4jawaly;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;

class ForgetPasswordController extends Controller
{
    use ApiResponseHelper;
    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $otp = '';
        for ($i = 0; $i < 4; $i++) {
        $otp .= mt_rand(0, 9); // Use mt_rand() for better randomness
        }

        DB::table('password_reset_tokens')->insert([
            'phone' => $request->phone,
            'code' => $request->code,
            'otp' => $otp,
            'created_at'=> Carbon::now()
        ]);
        // Sms4jawaly::sendSms($otp,$request->phone,  $request->code);
        return $this->setCode(200)->setMessage('We have send an Sms to you')->setData($otp)->send();

    }

    public function checkOTP (CheckOtpRequest $request)
    {
        $otp = DB::table('password_reset_tokens')
        ->where([
            "phone" =>$request->phone,
            "code" => $request->code,
            "otp" => $request->otp
        ])->first();
        if(!$otp){
            return $this->setCode(422)->setMessage('Sorry invalid OTP! Please Enter The Correct OTP')->send();
        }else{
            DB::table('password_reset_tokens')->where(["phone" =>$request->phone,"code" => $request->code,])->delete();
            return $this->setCode(200)->setMessage('Success')->send();
        }
    }
    public function resetPassword(ResetPasswordRequest $request)
    {

        $user = DB::table('users')
        ->where([
            "phone" =>$request->phone,
            "code" => $request->code,
        ])->first();

        if(!$user){
        return $this->setCode(422)->setMessage('Sorry invalid Phone! Please Try again')->send();
        }
        DB::table('users')->where(["phone" =>$request->phone,"code" => $request->code,])->update([
            'password'=> Hash::make($request->password) ?? $user->password,
        ]);
        return $this->setCode(200)->setMessage('Password Updated Successfully')->send();

    }
}
