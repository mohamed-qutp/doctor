<?php

namespace App\Http\Controllers\admin;

use App\Models\Title;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Traits\AuthorizeCheck;
use App\Helpers\ApiResponseHelper;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleUpdateRequest;
use Spatie\Permission\Models\Permission;

class DepartmentController extends Controller
{
    use AuthorizeCheck;
    use ApiResponseHelper;

        public function index ()
        {
            $departments = Department::select('id','name_' . App::currentLocale() . ' as name')->get();
            return $this->setCode(200)->setMessage('Successe')->setData($departments)->send();

        }
        public function allTitle ()
        {
            $titles = Title::select('id','name_' . App::currentLocale() . ' as name' )->get();
            return $this->setCode(200)->setMessage('Successe')->setData($titles)->send();

        }

        public function show ($id)
        {
            $this->authorizCheck('المشاهدة فقط');
            $departments = Department::select('id','name_' . App::currentLocale() . ' as name')->get();
            $titles = Title::select('id','name_' . App::currentLocale() . ' as name' )->where('department_id',$id)->get();
            $permissions = Permission::select('id','name' )->get();
            return $this->setCode(200)->setMessage('Successe')->setData(['titles'=>$titles,'departments'=>$departments,'permissions'=>$permissions])->send();
        }

        public function titlePermissions($id)
        {
            $this->authorizCheck('المشاهدة فقط');
            $role = Role::select('id', 'name')->where('title_id',$id)->with('permissions:id,name')->first();

            // $role["permissions"] = collect($permissions)->flatten(1)[0];
            return $this->setCode(200)->setMessage('Successe')->setData($role)->send();
        }
        public function update(RoleUpdateRequest $request, string $id)
        {
            $role = Role::where('title_id',$id)->first();
            $permissions = $role->permissions;
            $role->revokePermissionTo($permissions);
            $role->givePermissionTo($request->permissions);
            $role = $role->refresh();
            $role->permissions;
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
            return $this->setCode(200)->setMessage('Successe')->setData($role)->send();

        }


}
