<?php

namespace App\Http\Controllers\Statistic;

use App\Models\User;
use Carbon\Carbon;
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

    public function userdate(Request $request)
    {
        $users_count = User::all()->count();
        // Users created last week
        if ($request->filter == 'weekly')
        {
            $month = $request->month; // February (as an example)
            $year = $request->year;

            // Define the start and end dates of the month
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();

            // Initialize an array to store users for each week
            $users = [];

            // Iterate through each week of the month and filter users
            for ($week = 1; $week <= 4; $week++)
            {
                $startOfWeek = $startDate->copy()->addWeeks($week - 1)->startOfWeek();
                $endOfWeek = $startDate->copy()->addWeeks($week - 1)->endOfWeek();

                // Get users created within the specified week
                $users["Week $week"] = User::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();

            }
        }
        if ($request->filter == 'yearly') {
            $year = $request->year; // Specify the year (as an example)

            // Initialize an array to store users for each month
            $users = [];

            // Iterate through each month of the year and filter users
            for ($month = 1; $month <= 12; $month++) {
                // Define the start and end dates of the month
                $startDate = Carbon::create($year, $month, 1)->startOfMonth();
                $endDate = Carbon::create($year, $month, 1)->endOfMonth();

                // Get users created within the specified month
                $users[Carbon::create($year, $month, 1)->format('M')] = User::whereBetween('created_at', [$startDate, $endDate])->count();
            }
        }
        return $this->setCode(200)->setMessage('Successe')->setData(['users'=>$users, 'users_count' => $users_count])->send();
    }
}
