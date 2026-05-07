<?php
// 备案控制器
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/IcpRecord.php';

class RecordController {
    public function __construct() {
        // 检查登录状态
        if (!User::getCurrentUser()) {
            header('Location: /login');
            exit;
        }
    }
    
    public function add() {
        // 包含添加备案视图
        require_once __DIR__ . '/../views/record_add.php';
    }
    
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'user_id' => $_SESSION['user_id'],
                'site_name' => $_POST['site_name'] ?? '',
                'domain' => $_POST['domain'] ?? '',
                'homepage' => $_POST['homepage'] ?? '',
                'site_info' => $_POST['site_info'] ?? '',
                'other_icp_number' => $_POST['other_icp_number'] ?? '',
                'icp_community' => $_POST['icp_community'] ?? '',
                'owner' => $_POST['owner'] ?? '',
                'subject' => $_POST['subject'] ?? ''
            ];
            
            // 验证数据
            if (empty($data['site_name']) || empty($data['domain']) || empty($data['homepage']) || empty($data['site_info']) || empty($data['owner']) || empty($data['subject'])) {
                $error = '请填写所有必填字段';
                require_once __DIR__ . '/../views/record_add.php';
                return;
            }
            
            // 保存备案
            if (IcpRecord::add($data)) {
                // 保存成功，跳转到我的备案页
                header('Location: /record/my');
                exit;
            } else {
                // 保存失败，显示错误信息
                $error = '保存失败，请重试';
                require_once __DIR__ . '/../views/record_add.php';
            }
        }
    }
    
    public function my() {
        // 获取当前用户的备案记录
        $records = IcpRecord::getUserRecords($_SESSION['user_id']);
        
        // 包含我的备案视图
        require_once __DIR__ . '/../views/record_my.php';
    }
}
