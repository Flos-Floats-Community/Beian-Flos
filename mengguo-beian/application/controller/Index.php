<?php
namespace app\controller;

use app\model\Website;
use think\facade\View;

class Index
{
    public function index()
    {
        return View::fetch('index/search');
    }
    
    public function search()
    {
        $keyword = input('get.keyword', '', 'trim');
        
        if (empty($keyword)) {
            return redirect('/');
        }
        
        $website = Website::where(function ($query) use ($keyword) {
            $query->where('domain', '=', $keyword)
                  ->whereOr('f备案号', 'like', '%' . $keyword . '%');
        })->find();
        
        if (!$website) {
            $this->error('未找到相关备案信息', '/');
        }
        
        return redirect('/detail/' . $website->domain);
    }
    
    public function detail($domain)
    {
        $website = Website::where('domain', $domain)->find();
        
        if (!$website) {
            $this->error('未找到相关备案信息', '/');
        }
        
        View::assign([
            'website' => $website,
            'can_verify' => $website->can_verify ? '可以' : '不可',
            'verification' => $website->verification ? $website->verification->status_text : '无验证请求'
        ]);
        
        return View::fetch('index/detail');
    }
}