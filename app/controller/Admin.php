<?php
namespace app\controller;

use app\BaseController;
use app\model\Record;
use think\facade\Request;
use think\facade\Session;
use think\facade\View;
use think\facade\Db;

class Admin extends BaseController
{
    public function login()
    {
        if (Session::has('admin_id')) return redirect('/admin/flos/manage');
        if (Request::isPost()) {
            $user = Db::name('admins')->where('username', Request::post('username'))->find();
            if ($user && password_verify(Request::post('password'), $user['password'])) {
                Session::set('admin_id', $user['id']);
                return redirect('/admin/flos/manage');
            }
            return View::fetch('admin/login', ['error' => '用户名或密码错误']);
        }
        return View::fetch('admin/login');
    }

    public function manage()
    {
        if (!Session::has('admin_id')) return redirect('/admin/flos/login');

        if (Request::isPost()) {
            $id = Request::param('id/d');
            $status = Request::post('status');
            $floatIcp = trim(Request::post('float_icp'));
            $verified = (int)Request::post('entity_verified');

            if (!in_array($status, ['pending', 'verified', 'rejected'])) $status = 'pending';

            Record::update([
                'status' => $status,
                'float_icp' => ($status === 'verified') ? $floatIcp : null,
                'entity_verified' => $verified,
                'updated_at' => date('Y-m-d H:i:s')
            ], ['id' => $id]);

            return redirect('/admin/flos/manage');
        }

        $pending = Record::where('status', 'pending')->select();
        $verified = Record::where('status', 'verified')->select();
        return View::fetch('admin/manage', compact('pending', 'verified'));
    }

    public function logout()
    {
        Session::delete('admin_id');
        return redirect('/');
    }
}