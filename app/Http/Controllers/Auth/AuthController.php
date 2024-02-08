<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Helpers\UploadImage;
use App\Traits\MergeObjects;
use Illuminate\Http\Request;
use App\Traits\GetSenderName;
use App\Traits\ResetPassword;
use App\Helpers\ApiResponseHelper;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUserRequest;
use MFrouh\Sms4jawaly\Facades\Sms4jawaly;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    use MergeObjects;
    use ApiResponseHelper;
    use UploadImage;


    // Get All user Details
    public function profile()
    {
        $user = User::findOrFail(Auth::user()->id) ;
        $user = $this->toArray($user);
        return $this->setCode(200)->setMessage('Successe')->setData($user)->send();
    }
        //function to handle Register operation
    public function Register(RegisterUserRequest $request)
    {
        $name =$this->UploadImage($request, 'Users_images');
            $user = User::create([
                'name' => $request->name  ,
                'email'=> $request->email ,
                'password'=> Hash::make($request->password),
                'phone' => $request->phone ,
                'code' => $request->code ,
                'country_id'=>$request->country_id,
                'city_id'=>$request->city_id,
                'birth_date' =>$request->birth_date,
                'img'=> $name==null ? null : env('APP_URL').$name,
                'gender' =>$request->gender,
                'fcm_token' => $request->fcm_token
            ]);

        $token = $user->createToken("API TOKEN")->plainTextToken;
        $user = $this->toArray($user, $token);
        return $this->setCode(200)->setMessage('User Created Successfully')->setData($user)->send();
    }//End Method

    //function to handle Login operation
    public function Login(LoginRequest $request)
    {
        if (!Auth::attempt(['code' => $request->code, 'phone' => $request->phone, 'password' => $request->password]) )
        {
            $error = App::currentLocale() == 'en' ? 'the credintials is not correct' :  'رفم الموبايل او الباسورد غير صحيح ' ;
            return response()->json(['status' => false,'message' => $error, 'code' => 401], 401);
        }
        $user = User::where('phone',$request->phone)->first();
        $token = $user->createToken("API TOKEN")->plainTextToken;
        $user = $this->toArray($user, $token);
        return $this->setCode(200)->setMessage('User Logeed in Successfully')->setData($user)->send();
    }//End Method

    //function to allow users to Update thier settings
    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();
        $name =$this->UploadImage($request, 'Users_images', $user->img);
        $user->update([
            'name' => $request->name ?? $user->name ,
            'email'=> $request->email ?? $user->email ,
            'password'=> Hash::make($request->password) ?? $user->password,
            'phone' => $request->phone ?? $user->phone,
            'code' => $request->code ?? $user->code,
            'country_id'=>$request->country_id ?? $user->country_id,
            'city_id'=>$request->city_id ?? $user->city_id,
            'birth_date' =>$request->birth_date ?? $user->birth_date,
            'img'=> $name==null ? $user->img : env('APP_URL').$name,
            'gender' =>$request->gender ?? $user->gender ,
            'fcm_token' => $request->fcm_token
            ]);

        return $this->setCode(200)->setMessage('User Updated Successfully')->setData($user)->send();
    }//End Method

    //function to handle logout operation
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return $this->setCode(200)->setMessage('User Logged Out Successfully')->send();
    }//End Method

}
