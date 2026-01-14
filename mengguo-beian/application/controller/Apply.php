<?php
namespace app\controller;

use app\model\Website;
use app\model\Verification;
use app\model\User;
use think\facade\View;
use think\facade\Session;

class Apply
{
    public function index()
    {
        return View::fetch('apply/index');
    }
    
    public function submit()
    {
        if (!Session::has('user_id')) {
            return json(['code' => 403, 'msg' => '请先登录']);
        }
        
        $data = input('post.');
        
        // 验证表单数据
        $validate = new \think\Validate([
            'website_name' => 'require|max:100',
            'domain' => 'require|url',
            'homepage' => 'require|url',
            'site_info' => 'require|max:500',
            'f备案号' => 'require|max:50',
            'owner' => 'require|max:100',
            'subject' => 'require|max:100',
            'other备案号' => 'max:100',
            'community' => 'max:100',
        ]);
        
        if (!$validate->check($data)) {
            return json(['code' => 400, 'msg' => $validate->getError()]);
        }
        
        // 检查域名是否已存在
        if (Website::where('domain', $data['domain'])->find()) {
            return json(['code' => 400, 'msg' => '该域名已存在备案记录']);
        }
        
        // 创建网站记录
        $website = new Website();
        $website->website_name = $data['website_name'];
        $website->domain = $data['domain'];
        $website->homepage = $data['homepage'];
        $website->site_info = $data['site_info'];
        $website->f备案号 = $data['f备案号'];
        $website->other备案号 = $data['other备案号'] ?? '';
        $website->community = $data['community'] ?? '';
        $website->owner = $data['owner'];
        $website->subject = $data['subject'];
        $website->status = Website::STATUS_SUSPENDED; // 待审核状态
        $website->can_verify = false;
        $website->user_id = Session::get('user_id');
        $website->save();
        
        // 创建验证请求
        $verification = new Verification();
        $verification->website_id = $website->id;
        $verification->user_id = Session::get('user_id');
        $verification->type = 'subject';
        $verification->status = Verification::STATUS_PENDING;
        $verification->save();
        
        return json(['code' => 200, 'msg' => '备案申请已提交，请等待审核', 'url' => '/']);
    }
}