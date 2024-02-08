<?php

namespace App\Http\Controllers\Home;

use App\Models\Banner;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Opinion;
use Illuminate\Support\Facades\App;

class HomeController extends Controller
{

    public function index(){
            $banners = Banner::all();
            $categories = Category::select('id','name_' . App::currentLocale() . ' as name' ,'description_'. App::currentLocale() . ' as description' , 'img' )->get();
            $opinons =  Opinion::orderby('id', 'DESC')->with('user','category')->limit(5)->get();

            return response()->json([
                'status' => 200,
                'message' => 'Data returned successfully',
                'data' => ['categories' => $categories,'opinons'=> $opinons, 'banners'=> $banners]
            ],200);
    }
}
