<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Title;
use App\Models\Department;
use App\Traits\MergeObjects;
use Illuminate\Http\Request;
use App\Traits\AuthorizeCheck;
use App\Helpers\ApiResponseHelper;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssignRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateUsersRequest;
use Illuminate\Support\Facades\Validator;


class UsersController extends Controller
{
    use AuthorizeCheck;
    use MergeObjects;
    use ApiResponseHelper;

    // public function index (Request $request)
    // {
    //     $per_page = (int) ($request->per_page ?? 10);
    //     $pageNumber = (int) ($request->current_page ?? 1);
    //     if (isset($request->user_type)) {
    //         $users = User::orderBy("id","desc")->where('user_type',$request->user_type)->paginate($per_page, ['*'], 'page', $pageNumber);
    //     }
    //     else {
    //         $users = User::orderBy("id","desc")->paginate($per_page, ['*'], 'page', $pageNumber);
    //     }
    //     return $this->setCode(200)->setMessage('Successe')->setData($users->items())->send();
    // }
        public function search(Request $request)
    {
        $this->authorizCheck('المشاهدة فقط');
        $per_page = (int) ($request->per_page ?? 10);
        $pageNumber = (int) ($request->current_page ?? 1);
        $keyword = $request->keyword ;
        if (isset($request->user_type)) {
            $users = User::orderBy("id","desc")->with('title:id,name_ar,name_en','department:id,name_ar,name_en','country','city')
            ->whereIn('user_type',$request->user_type)
            ->where('name','like',"%$keyword%")
            ->paginate($per_page, ['*'], 'page', $pageNumber);
        }
        else {
            $users = User::orderBy("id","desc")->with('title:id,name_ar,name_en','department:id,name_ar,name_en','country','city')
            ->where('name','like',"%$keyword%")
            ->paginate($per_page, ['*'], 'page', $pageNumber);
        }
        return $this->setCode(200)->setMessage('Date Returned Successfully')->setData($users->items())->send();
    }

    public function delete (string $id)
    {
        $this->authorizCheck('حذف المستخدمين');
        $user = User::findOrFail($id);

        if($user->img != null)
        {
            if (File::exists($user->img)) {
                File::delete($user->img);
                }
        }
            User::find($id)->delete();
            return $this->setCode(200)->setMessage('User Deleted Successfully')->send();
    }
    public function assign (AssignRequest $request)
    {
        $this->authorizCheck('انشاء المستخدمين');
        $department = Department::findOrFail($request->department_id);
        $user = User::create([
                'name' => $request->name  ,
                'password'=> Hash::make($request->password),
                'phone' => $request->phone ,
                'code' => $request->code ,
                'department_id' => $request->department_id,
                'title_id' => $request->title_id,
                'user_type'=>$department->name_en
            ]);
            $role = Role::where('title_id',$request->title_id)->first();
            $user->assignRole($role->id);
            $user = $this->toArray($user);
            return $this->setCode(200)->setMessage('User Created Successfully')->setData($user)->send();
    }
    //Function To Update users role
    public function update(UpdateUsersRequest $request, $id)
    {
        $this->authorizCheck('انشاء المستخدمين');
        $user = User::findOrFail($id);
        if($request->has('department_id'))
        {
            $department = Department::findOrFail($request->department_id);
        }
        else{
            $department =Department::findOrFail( $user->department_id);
        }
        $user->update([
            'name' => $request->name ?? $user->name  ,
            'password'=> Hash::make($request->password) ?? $user->password,
            'phone' => $request->phone ?? $user->phone,
            'code' => $request->code ?? $user->code,
            'department_id' => $request->department_id ?? $user->department_id,
            'title_id' => $request->title_id ?? $user->title_id,
            'user_type'=>$department->name_en
        ]);
        $role = Role::where('title_id',$request->title_id)->first();
            $user->assignRole($role->id);
            $user = $this->toArray($user);
            return $this->setCode(200)->setMessage('User Updated Successfully')->setData($user)->send();
    }//End Method
}
