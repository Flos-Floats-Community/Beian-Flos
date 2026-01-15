<?php
use think\facade\Route;

// 首页
Route::get('/', 'Index/index');

// 用户路由
Route::get('/user/register', 'User/register');
Route::post('/user/register', 'User/register');
Route::get('/user/login', 'User/login');
Route::post('/user/login', 'User/login');
Route::get('/user/apply', 'User/apply');
Route::post('/user/apply', 'User/apply');
Route::get('/user/logout', 'User/logout');

// 管理员路由
Route::get('/admin/flos/login', 'Admin/login');
Route::post('/admin/flos/login', 'Admin/login');
Route::get('/admin/flos/manage', 'Admin/manage');
Route::post('/admin/flos/manage', 'Admin/manage');
Route::get('/admin/flos/logout', 'Admin/logout');