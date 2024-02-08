<?php

namespace App\Http\Controllers\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Helpers\ApiResponseHelper;
use App\Helpers\UploadImage;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CategoryStoreRequest;


class CategoryController extends Controller
{
    use ApiResponseHelper;
    use UploadImage;

    public function index() {
        $categories = Category::select('id','name_' . App::currentLocale() . ' as name' ,'description_'. App::currentLocale() . ' as description' , 'img' )->get();
        return $this->setCode(200)->setMessage('Successe')->setData($categories)->send();
    }
    // public function store(CategoryStoreRequest $request)
    // {
    //     $name =$this->UploadImage($request, 'Category_images');
    //     $category = Category::create([
    //         'img'=> env('APP_URL').$name,
    //         'name_ar'=> $request->name_ar,
    //         'name_en'=> $request->name_en,
    //         'description_en'=> $request->description_en,
    //         'description_ar'=> $request->description_ar,
    //     ]);
    //     return $this->setCode(200)->setMessage('Successe')->setData($category)->send();
    // }

    public function show (string $id )
    {
        $category = Category::select('id','name_' . App::currentLocale() . ' as name' ,'description_'. App::currentLocale() . ' as description' , 'img' )->where('id', $id )->first();
        return $this->setCode(200)->setMessage('Successe')->setData($category)->send();

    }

}