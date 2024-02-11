<?php

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\Banner\BannerController;
use App\Http\Controllers\Article\ArticleController;
use App\Http\Controllers\Country\CountryController;
use App\Http\Controllers\Opinion\OpinionController;
use App\Http\Controllers\admin\DepartmentController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Statistic\StatisticController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\Consultatoins\ConsultationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::group(['middleware' => ["SetLang"]], function () {
    //Authneticated Only
    Route::group(['middleware' => ["auth:sanctum"]], function () {
            //Assign Users
            Route::post('/assign',[UsersController::class,'assign']);
            //edit Users
            Route::post('/change/role/{id}',[UsersController::class,'update']);
             //Departments
            Route::get('/department',[DepartmentController::class,'index']);
             //Show Permissions In title
            Route::get('/title/permissions/{id}',[DepartmentController::class,'titlePermissions']);
            //Departments
            Route::get('/title/department/{id}',[DepartmentController::class,'show']);
            //store Categories
            Route::post('/categories/store',[CategoryController::class, 'store']);
            //delete Opinions
            Route::get('/opinion/delete/{id}',[OpinionController::class,'destroy']);
            //Search Users
            Route::post('/search/users',[UsersController::class,'search']);
            //Search Users
            Route::post('/users',[UsersController::class,'index']);
            //Delete user
            Route::get('/delete/users/{id}',[UsersController::class,'delete']);
            //Articles
            Route::resource('/article',ArticleController::class)->only(['store', 'update', 'destroy']);
            //Banner Resource
            Route::resource('/banners',BannerController::class)->only(['store', 'update', 'destroy']);
            //adminHome
            Route::post('/admin/home',[AdminController::class,'index']);
            //statistics
            Route::get('/statistic/users',[StatisticController::class,'userStatistcs']);
            Route::get('/statistic/articles',[StatisticController::class,'articleStatistics']);
            Route::post('/statistic/userdate', [StatisticController::class, 'userdate']);
            //Consultations
            Route::post('/statues/store',[ConsultationController::class,'store']);
            Route::get('/statues',[ConsultationController::class,'index']);
            //Roles & Permission
            Route::post('/update/role/{id}',[DepartmentController::class,'update']);
            //Profile
            Route::get('/profile', [AuthController::class, 'profile']);
            //Logout
            Route::post('/logout', [AuthController::class, 'logout']);
            //Update
            Route::post('/user/update', [AuthController::class, 'update']);
            //Opinios
            Route::post('/opinion/store',[OpinionController::class,'store']);
            //Notifications
            Route::post('/get/notification', [NotificationController::class, 'index']);
            // fctoken
            Route::get('/topic', [NotificationController::class, 'fctoken']);
    });
        //Registeration
        Route::post('/register',[AuthController::class,'Register']);
        //Login
        Route::post('/login',[AuthController::class,'Login']);

        //Home
        Route::get('/home', [HomeController::class,'index']);
        //Cities
        Route::get('/country',[CountryController::class,'index']);
        Route::get('/city/show/{id}',[CountryController::class,'show']);

        //get Opinions per Category
        Route::post('/opinion/category',[OpinionController::class,'show_opinions_per_category']);

        //Articles
        Route::resource('/article',ArticleController::class)->only(['show']);
        Route::post('/article/paginate',[ArticleController::class,'index']);
        //Banner
        //Banner Resource
        Route::resource('/banners',BannerController::class)->only(['index', 'show']);

        //Categories
        Route::get('/categories',[CategoryController::class,'index']);
        //show
        Route::get('/categories/show/{id}',[CategoryController::class,'show']);
        //search All Articles
        Route::post('/search/articles', [ArticleController::class, 'searchAll']);

        //Forget Password
        Route::post('/forget-password',[ForgetPasswordController::class,'forgetPassword']);
        //check OTP
        Route::post('/check/otp',[ForgetPasswordController::class,'checkOTP']);
        //Reset Pasword
        Route::post('/reset-password',[ForgetPasswordController::class,'resetPassword']);
});
