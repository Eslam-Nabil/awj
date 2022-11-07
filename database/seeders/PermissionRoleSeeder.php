<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $models=['user','article','type','section','category','team'];
        foreach ($models as $key => $model) {
            Permission::create(['name' => 'create.'.$model]);
            Permission::create(['name' => 'edit.'.$model]);
            Permission::create(['name' => 'view.'.$model]);
            Permission::create(['name' => 'delete.'.$model]);
        }
        Permission::create(['name' => 'approve.article']);

        $publisher_role=Role::create(['name' => 'publisher']);
        $author_role=   Role::create(['name' => 'author']);
        $reviewer_role= Role::create(['name' => 'reviewer']);
        $admin_role =   Role::create(['name' => 'admin']);
        $student_role=  Role::create(['name' => 'student']);

        $admin_role->givePermissionTo(Permission::all());
        $publisher_role->givePermissionTo([
            'view.article',
            'approve.article'
        ]);
        $reviewer_role->givePermissionTo([
            'view.article',
        ]);



    }
}
