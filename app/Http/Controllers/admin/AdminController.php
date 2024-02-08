<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Opinion;
use Illuminate\Http\Request;
use App\Traits\AuthorizeCheck;
use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    use ApiResponseHelper;
    use AuthorizeCheck;
    public function index(Request $request)
    {
        // $this->authorizCheck('المشاهدة فقط');
        $per_page = (int) ($request->per_page ?? 10);
        $pageNumber = (int) ($request->current_page ?? 1);
        $opinions = Opinion::select('id', 'content', 'rate', 'category_id', 'user_id')->with('category','user')->paginate($per_page, ['*'], 'page', $pageNumber);
        $users = User::get()->count('id');
        return $this->setCode(200)->setMessage('Successe')->setData(['opinions' =>$opinions->items(), 'users_Count' => $users])->send();
    }
}
