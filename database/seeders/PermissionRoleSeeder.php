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
        Permission::create(['name' => 'create-article-posts']);
        Permission::create(['name' => 'edit-article-posts']);
        Permission::create(['name' => 'view-article-posts']);
        Permission::create(['name' => 'delete-article-posts']);
        Permission::create(['name' => 'create-user']);
        Permission::create(['name' => 'edit-user']);
        Permission::create(['name' => 'delete-user']);

        $publisher_role=Role::create(['name' => 'publisher']);
        $author_role=   Role::create(['name' => 'author']);
        $reviewer_role= Role::create(['name' => 'reviewer']);
        $admin_role =   Role::create(['name' => 'admin']);
        $student_role=  Role::create(['name' => 'student']);

        $admin_role->givePermissionTo([
            'create-user',
            'edit-user',
            'delete-user',
            'create-article-posts',
            'edit-article-posts',
            'delete-article-posts',
        ]);
        $publisher_role->givePermissionTo([
            'view-article-posts',
        ]);
        $reviewer_role->givePermissionTo([
            'view-article-posts',
        ]);



    }
}
