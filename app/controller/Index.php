<?php
namespace app\controller;

use app\BaseController;
use app\model\Record;
use think\facade\View;

class Index extends BaseController
{
    public function index()
    {
        $records = Record::where('status', 'verified')
                         ->field('site_name,domain,homepage,info,float_icp,national_icp,community,owner,entity,updated_at,status,entity_verified,report_url')
                         ->order('updated_at', 'desc')
                         ->select();
        return View::fetch('index/index', ['records' => $records]);
    }
}