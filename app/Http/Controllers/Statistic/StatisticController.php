<?php

namespace App\Http\Controllers\Statistic;

use App\Models\User;
use App\Models\Article;
use App\Models\Country;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Traits\AuthorizeCheck;
use App\Helpers\ApiResponseHelper;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;

class StatisticController extends Controller
{
    use ApiResponseHelper;
    use AuthorizeCheck;

    public function userStatistcs ()
    {
        $this->authorizCheck('المشاهدة فقط');
        $users_count = User::all()->count();
        $males_count = User::where('gender','male')->count();
        $females_count = User::where('gender','female')->count();
        $coutries_statistic = [];

        foreach(Country::all() as $country)
        {
            $no_users_per_country = User::all()->where('country_id',$country->id)->count();
            // $country_name = $country->name;
            if( App::getLocale() == 'en'){
                $coutries_statistic[$country->name_en] = $no_users_per_country;
            }
            else {
                $coutries_statistic[$country->name_ar] = $no_users_per_country;
            }
            // $coutries_statistic[$country->id] = $country_name;
        }
        return $this->setCode(200)->setMessage('Successe')->setData(['users_count'=>$users_count,'males_count' =>$males_count ,'females_count'=>$females_count,'coutries_statistic'=>$coutries_statistic])->send();
    }

    public function articleStatistics ()
    {
        $this->authorizCheck('المشاهدة فقط');
        $articles_statistic = [];
        $articles_count = Article::all()->count();
        foreach(Category::all() as $category)
        {
            $no_article_per_category = Article::all()->where('category_id',$category->id)->count();
            // $country_name = $country->name;
            if( App::getLocale() == 'en'){
                $articles_statistic[$category->name_en] = $no_article_per_category;
            }
            else {
                $articles_statistic[$category->name_ar] = $no_article_per_category;
            }
            // $coutries_statistic[$country->id] = $country_name;
        }
        return $this->setCode(200)->setMessage('Successe')->setData(['articles_statistic'=>$articles_statistic, 'articles_count'=>$articles_count])->send();
    }


}