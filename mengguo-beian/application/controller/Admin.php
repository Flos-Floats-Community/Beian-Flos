<?php
namespace app\controller;

use app\model\Verification;
use app\model\Website;
use think\facade\View;
use think\facade\Session;

class Admin
{
    public function login()
    {
        if (Session::has('admin_id')) {
            return redirect('/admin/dashboard');
        }
        return View::fetch('admin/login');
    }
    
    public function doLogin()
    {
        $data = input('post.');
        
        // 简单验证，实际应用中应使用更安全的方式
        if ($data['username'] === 'admin' && $data['password'] === 'flos123456') {
            Session::set('admin_id', 1);
            Session::set('admin_name', '管理员');
            return json(['code' => 200, 'msg' => '登录成功', 'url' => '/admin/dashboard']);
        }
        
        return json(['code' => 401, 'msg' => '用户名或密码错误']);
    }
    
    public function dashboard()
    {
        $totalWebsites = Website::count();
        $pendingVerifications = Verification::where('status', Verification::STATUS_PENDING)->count();
        $normalWebsites = Website::where('status', Website::STATUS_NORMAL)->count();
        
        View::assign([
            'totalWebsites' => $totalWebsites,
            'pendingVerifications' => $pendingVerifications,
            'normalWebsites' => $normalWebsites
        ]);
        
        return View::fetch('admin/dashboard');
    }
    
    public function verifications()
    {
        $verifications = Verification::with(['website', 'user'])
            ->order('id', 'desc')
            ->paginate(10);
        
        View::assign('verifications', $verifications);
        return View::fetch('admin/verifications');
    }
    
    public function verify($id)
    {
        $verification = Verification::find($id);
        
        if (!$verification) {
            return json(['code' => 404, 'msg' => '验证请求不存在']);
        }
        
        $verification->status = Verification::STATUS_APPROVED;
        $verification->save();
        
        // 更新网站状态
        $website = $verification->website;
        $website->status = Website::STATUS_NORMAL;
        $website->can_verify = true;
        $website->save();
        
        return json(['code' => 200, 'msg' => '验证已通过']);
    }
    
    public function reject($id)
    {
        $verification = Verification::find($id);
        
        if (!$verification) {
            return json(['code' => 404, 'msg' => '验证请求不存在']);
        }
        
        $verification->status = Verification::STATUS_REJECTED;
        $verification->save();
        
        // 更新网站状态
        $website = $verification->website;
        $website->status = Website::STATUS_REJECTED;
        $website->save();
        
        return json(['code' => 200, 'msg' => '验证已拒绝']);
    }
}