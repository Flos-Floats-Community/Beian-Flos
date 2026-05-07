<?php
// 登录控制器
require_once __DIR__ . '/../models/User.php';

class LoginController {
    public function index() {
        // 包含登录视图
        require_once __DIR__ . '/../views/login.php';
    }
    
    public function register() {
        // 包含注册视图
        require_once __DIR__ . '/../views/register.php';
    }
    
    public function doLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (User::login($username, $password)) {
                // 登录成功，跳转到首页
                header('Location: /');
                exit;
            } else {
                // 登录失败，显示错误信息
                $error = '用户名或密码错误';
                require_once __DIR__ . '/../views/login.php';
            }
        }
    }
    
    public function doRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $email = $_POST['email'] ?? '';
            
            if (User::register($username, $password, $email)) {
                // 注册成功，跳转到登录页
                header('Location: /login');
                exit;
            } else {
                // 注册失败，显示错误信息
                $error = '用户名或邮箱已存在';
                require_once __DIR__ . '/../views/register.php';
            }
        }
    }
    
    public function logout() {
        User::logout();
        // 退出成功，跳转到首页
        header('Location: /');
        exit;
    }
}
