<?php
namespace app\controller;

use app\model\User;
use think\facade\View;
use think\facade\Session;
use think\facade\Cookie;

class User
{
    public function login()
    {
        if (Session::has('user_id')) {
            return redirect('/');
        }
        return View::fetch('user/login');
    }
    
    public function doLogin()
    {
        $data = input('post.');
        
        $validate = new \think\Validate([
            'username' => 'require|max:50',
            'password' => 'require|min:6',
        ]);
        
        if (!$validate->check($data)) {
            return json(['code' => 400, 'msg' => $validate->getError()]);
        }
        
        $user = User::where('username', $data['username'])->find();
        
        if (!$user || !$user->verifyPassword($data['password'])) {
            return json(['code' => 401, 'msg' => '用户名或密码错误']);
        }
        
        Session::set('user_id', $user->id);
        Session::set('username', $user->username);
        
        return json(['code' => 200, 'msg' => '登录成功', 'url' => '/']);
    }
    
    public function register()
    {
        if (Session::has('user_id')) {
            return redirect('/');
        }
        return View::fetch('user/register');
    }
    
    public function doRegister()
    {
        $data = input('post.');
        
        $validate = new \think\Validate([
            'username' => 'require|max:50|unique:user',
            'password' => 'require|min:6',
            'confirm_password' => 'require|confirm:password',
            'email' => 'require|email',
        ]);
        
        if (!$validate->check($data)) {
            return json(['code' => 400, 'msg' => $validate->getError()]);
        }
        
        $user = new User();
        $user->username = $data['username'];
        $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->email = $data['email'];
        $user->save();
        
        return json(['code' => 200, 'msg' => '注册成功，请登录', 'url' => '/login']);
    }
    
    public function logout()
    {
        Session::delete('user_id');
        Session::delete('username');
        return redirect('/');
    }
}