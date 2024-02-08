<?php
namespace App\Traits;
use App\Models\User;
use App\Models\Title;
use App\Models\Department;
use Illuminate\Support\Facades\App;


trait MergeObjects {
    public function toArray($user, $token = null)
    {
        $user = User::findOrFail($user->id);
        $title = Title::select('name_' . App::currentLocale() . " as title" )
        ->where("id",$user->title_id)->first();
        $department = Department::select('name_' . App::currentLocale() . ' as department')
        ->where("id",$user->department_id)->first();
        $user["title"] = collect($title)->flatten(1)[0];
        $user["department"] = collect($department)->flatten(1)[0];

        if($token)
        {
            $user["token"] = collect($token)->flatten(1)[0];
        }

        return $user;

    }
}