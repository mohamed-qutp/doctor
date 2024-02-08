<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $arrayOfPermissionNames = [
            'تعديل الاعلانات',
            'انشاء المستخدمين',
            'حذف المستخدمين',
            'حذف الآراء',
            'انشاء المقالات',
            'تعديل المقالات',
            'حذف المقالات',
            'انشاء الوظائف',
            'تعديل الوظائف',
            'الرد على الرسائل',
            'المشاهدة فقط',
        ];

        $permissions = collect($arrayOfPermissionNames)->map(function($permission){
            return ['name' => $permission, 'guard_name' => 'web'];
        });
        Permission::insert($permissions->toArray());

        $role = Role::create(['name' => 'super admin'])->givePermissionTo($arrayOfPermissionNames);
    }
}