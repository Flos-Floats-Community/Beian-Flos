<?php
// +----------------------------------------------------------------------
// | 路由设置
// +----------------------------------------------------------------------

use think\facade\Route;

// 前台页面
Route::get('/', 'index/index');
Route::get('search', 'index/search');
Route::get('detail/:domain', 'index/detail');
Route::get('apply', 'apply/index');
Route::post('apply/submit', 'apply/submit');

// 用户相关
Route::get('login', 'user/login');
Route::post('login', 'user/doLogin');
Route::get('register', 'user/register');
Route::post('register', 'user/doRegister');
Route::get('logout', 'user/logout');

// 管理后台
Route::get('admin/flos', 'admin/login');
Route::post('admin/flos', 'admin/doLogin');
Route::group('admin', function () {
    Route::get('dashboard', 'admin/dashboard');
    Route::get('verifications', 'admin/verifications');
    Route::post('verifications/:id/verify', 'admin/verify');
    Route::post('verifications/:id/reject', 'admin/reject');
})->middleware(\app\middleware\AdminCheck::class);

// 创建中间件目录
if (!is_dir(app()->getAppPath() . 'middleware')) {
    mkdir(app()->getAppPath() . 'middleware', 0755, true);
}