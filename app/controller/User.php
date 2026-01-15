<?php
namespace app\controller;

use app\BaseController;
use app\model\Record;
use think\facade\Request;
use think\facade\Session;
use think\facade\View;
use think\facade\Db;

class User extends BaseController
{
    public function register()
    {
        if (Session::has('user_id')) return redirect('/user/apply');
        if (Request::isPost()) {
            $username = trim(Request::post('username'));
            $email = trim(Request::post('email'));
            $password = Request::post('password');
            $confirm = Request::post('confirm');

            if ($password !== $confirm) return View::fetch('user/register', ['error' => '两次密码不一致']);
            if (strlen($password) < 6) return View::fetch('user/register', ['error' => '密码至少6位']);
            if (Db::name('users')->where('username', $username)->find()) {
                return View::fetch('user/register', ['error' => '用户名已存在']);
            }

            Db::name('users')->insert([
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'created_at' => date('Y-m-d H:i:s')
            ]);

            Session::set('user_id', Db::getLastInsID());
            Session::set('user_name', $username);
            return redirect('/user/apply');
        }
        return View::fetch('user/register');
    }

    public function login()
    {
        if (Session::has('user_id')) return redirect('/user/apply');
        if (Request::isPost()) {
            $user = Db::name('users')->where('username', Request::post('username'))->find();
            if ($user && password_verify(Request::post('password'), $user['password'])) {
                Session::set('user_id', $user['id']);
                Session::set('user_name', $user['username']);
                return redirect('/user/apply');
            }
            return View::fetch('user/login', ['error' => '用户名或密码错误']);
        }
        return View::fetch('user/login');
    }

    public function apply()
    {
        if (!Session::has('user_id')) return redirect('/user/login');
        if (Request::isPost()) {
            $data = [
                'site_name' => trim(Request::post('site_name')),
                'domain' => trim(Request::post('domain')),
                'homepage' => trim(Request::post('homepage')),
                'info' => trim(Request::post('info')),
                'national_icp' => trim(Request::post('national_icp')),
                'community' => trim(Request::post('community')),
                'owner' => Session::get('user_name'),
                'entity' => trim(Request::post('entity')),
                'report_url' => trim(Request::post('report_url')),
                'updated_at' => date('Y-m-d H:i:s'),
                'status' => 'pending'
            ];

            try {
                Record::create($data);
                return View::fetch('user/apply', ['success' => '备案申请已提交，请等待管理员审核。']);
            } catch (\Exception $e) {
                if (strpos($e->getMessage(), 'UNIQUE constraint failed: records.domain') !== false) {
                    return View::fetch('user/apply', ['error' => '该域名已提交备案申请，请勿重复提交。']);
                }
                return View::fetch('user/apply', ['error' => '提交失败：' . $e->getMessage()]);
            }
        }
        return View::fetch('user/apply');
    }

    public function logout()
    {
        Session::clear();
        return redirect('/');
    }
}