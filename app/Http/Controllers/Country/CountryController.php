<?php

namespace App\Http\Controllers\Country;

use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Helpers\ApiResponseHelper;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;


class CountryController extends Controller
{
    use ApiResponseHelper;

public function index ()
{

    $countries = Country::select('id','name_' . App::currentLocale() . ' as name','code' , 'dial_code'  )->get();

    return $this->setCode(200)->setMessage('Successe')->setData($countries)->send();

}

    public function show ($id)
    {
        $cities = City::select('id','name_' . App::currentLocale() . ' as name' )->where('country_id',$id)->get();
        return $this->setCode(200)->setMessage('Successe')->setData($cities)->send();

    }
}