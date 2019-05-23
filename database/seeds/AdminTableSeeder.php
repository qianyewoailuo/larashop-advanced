<?php

use Illuminate\Database\Seeder;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Auth\Database\Menu;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create a user.
        Administrator::truncate();
        Administrator::create([
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'name'     => 'Administrator',
        ]);

        // create a role.
        Role::truncate();
        Role::create([
            'name' => 'Administrator',
            'slug' => 'administrator',
        ]);

        // add role to user.
        Administrator::first()->roles()->save(Role::first());

        //create a permission
        Permission::truncate();
        Permission::insert([
            [
                'name'        => 'All permission',
                'slug'        => '*',
                'http_method' => '',
                'http_path'   => '*',
            ],
            [
                'name'        => 'Dashboard',
                'slug'        => 'dashboard',
                'http_method' => 'GET',
                'http_path'   => '/',
            ],
            [
                'name'        => 'Login',
                'slug'        => 'auth.login',
                'http_method' => '',
                'http_path'   => "/auth/login\r\n/auth/logout",
            ],
            [
                'name'        => 'User setting',
                'slug'        => 'auth.setting',
                'http_method' => 'GET,PUT',
                'http_path'   => '/auth/setting',
            ],
            [
                'name'        => 'Auth management',
                'slug'        => 'auth.management',
                'http_method' => '',
                'http_path'   => "/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs",
            ],
            [
                'name'        => '用户管理',
                'slug'        => 'users',
                'http_method' => '',
                'http_path'   => "/users*",
            ],[
                'name'        => '商品管理',
                'slug'        => 'products',
                'http_method' => '',
                'http_path'   => "/products*",
            ],[
                'name'        => '订单管理',
                'slug'        => 'orders',
                'http_method' => '',
                'http_path'   => "/orders*",
            ],[
                'name'        => '优惠券管理',
                'slug'        => 'coupon_codes',
                'http_method' => '',
                'http_path'   => "/coupon_codes*",
            ],
        ]);

        Role::first()->permissions()->save(Permission::first());

        // add default menus.
        Menu::truncate();
        Menu::insert([
            [
                'parent_id' => 0,
                'order'     => 1,
                'title'     => '首页',
                'icon'      => 'fa-bar-chart',
                'uri'       => '/',
            ],
            [
                'parent_id' => 0,
                'order'     => 2,
                'title'     => '用户管理',
                'icon'      => 'fa-users',
                'uri'       => '/users',
            ],
            [
                'parent_id' => 0,
                'order'     => 3,
                'title'     => '商品管理',
                'icon'      => 'fa-cubes',
                'uri'       => '/products',
            ],
            [
                'parent_id' => 0,
                'order'     => 4,
                'title'     => '订单管理',
                'icon'      => 'fa-rmb',
                'uri'       => '/orders',
            ],
            [
                'parent_id' => 0,
                'order'     => 5,
                'title'     => '优惠券管理',
                'icon'      => 'fa-tags',
                'uri'       => '/coupon_codes',
            ],
            [
                'parent_id' => 0,
                'order'     => 6,
                'title'     => '系统管理',
                'icon'      => 'fa-tasks',
                'uri'       => '',
            ],
            [
                'parent_id' => 6,
                'order'     => 7,
                'title'     => '管理员',
                'icon'      => 'fa-users',
                'uri'       => 'auth/users',
            ],
            [
                'parent_id' => 6,
                'order'     => 8,
                'title'     => '角色',
                'icon'      => 'fa-user',
                'uri'       => 'auth/roles',
            ],
            [
                'parent_id' => 6,
                'order'     => 9,
                'title'     => '权限',
                'icon'      => 'fa-ban',
                'uri'       => 'auth/permissions',
            ],
            [
                'parent_id' => 6,
                'order'     => 10,
                'title'     => '菜单',
                'icon'      => 'fa-bars',
                'uri'       => 'auth/menu',
            ],
            [
                'parent_id' => 6,
                'order'     => 11,
                'title'     => '操作日志',
                'icon'      => 'fa-history',
                'uri'       => 'auth/logs',
            ],
        ]);

        // add role to menu.
        Menu::find(2)->roles()->save(Role::first());
    }
}
