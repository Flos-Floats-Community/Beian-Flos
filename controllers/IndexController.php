<?php
// 首页控制器
require_once __DIR__ . '/../models/IcpRecord.php';

class IndexController {
    public function index() {
        // 获取所有已通过的备案记录
        $records = IcpRecord::getAllApproved();
        
        // 包含视图
        require_once __DIR__ . '/../views/index.php';
    }
}
