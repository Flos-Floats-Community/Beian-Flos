<?php
// 管理员控制器
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/IcpRecord.php';
require_once __DIR__ . '/../models/Database.php';

class AdminController {
    public function __construct() {
        // 检查登录状态
        if (!User::getCurrentUser()) {
            header('Location: /login');
            exit;
        }
        
        // 检查是否为管理员
        if (!User::isAdmin()) {
            header('Location: /');
            exit;
        }
    }
    
    public function index() {
        // 获取待审核的备案记录
        $records = IcpRecord::getPendingRecords();
        
        // 包含管理视图
        require_once __DIR__ . '/../views/admin.php';
    }
    
    public function verify($id) {
        // 审核通过备案
        if (IcpRecord::approve($id)) {
            // 审核成功，跳转到管理页
            header('Location: /admin/flos');
            exit;
        } else {
            // 审核失败，显示错误信息
            $error = '审核失败';
            $records = IcpRecord::getPendingRecords();
            require_once __DIR__ . '/../views/admin.php';
        }
    }
    
    public function reject($id) {
        // 拒绝备案
        if (IcpRecord::reject($id)) {
            // 拒绝成功，跳转到管理页
            header('Location: /admin/flos');
            exit;
        } else {
            // 拒绝失败，显示错误信息
            $error = '拒绝失败';
            $records = IcpRecord::getPendingRecords();
            require_once __DIR__ . '/../views/admin.php';
        }
    }
    
    public function users() {
        // 获取所有用户
        $stmt = Database::query('SELECT * FROM users ORDER BY create_time DESC');
        $users = $stmt->fetchAll();
        
        // 包含用户管理视图
        require_once __DIR__ . '/../views/admin_users.php';
    }
    
    public function editUser($id) {
        // 获取用户信息
        $stmt = Database::query('SELECT * FROM users WHERE id = ?', [$id]);
        $user = $stmt->fetch();
        
        if (!$user) {
            header('Location: /admin/users');
            exit;
        }
        
        // 处理表单提交
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $role = $_POST['role'] ?? 0;
            
            // 更新用户
            $stmt = Database::query(
                'UPDATE users SET username = ?, email = ?, role = ?, update_time = CURRENT_TIMESTAMP WHERE id = ?',
                [$username, $email, $role, $id]
            );
            
            header('Location: /admin/users');
            exit;
        }
        
        // 包含编辑用户视图
        require_once __DIR__ . '/../views/admin_edit_user.php';
    }
    
    public function deleteUser($id) {
        // 防止删除管理员账户
        if ($id == 1) {
            header('Location: /admin/users');
            exit;
        }
        
        // 删除用户
        $stmt = Database::query('DELETE FROM users WHERE id = ?', [$id]);
        
        header('Location: /admin/users');
        exit;
    }
    
    public function checkUpdate() {
        // 检查是否有升级包
        $updatePath = APP_ROOT . '/update/upgradeit.zip';
        $hasUpdate = file_exists($updatePath);
        
        // 返回检查结果
        if ($hasUpdate) {
            echo json_encode(['status' => 'success', 'hasUpdate' => true]);
        } else {
            echo json_encode(['status' => 'success', 'hasUpdate' => false]);
        }
        exit;
    }
    
    public function upgrade() {
        // 检查是否有升级包
        $updatePath = APP_ROOT . '/update/upgradeit.zip';
        if (!file_exists($updatePath)) {
            echo json_encode(['status' => 'error', 'message' => '升级包不存在']);
            exit;
        }
        
        try {
            // 创建临时目录
            $tempDir = APP_ROOT . '/update/temp';
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0777, true);
            }
            
            // 解压升级包
            $zip = new ZipArchive();
            if ($zip->open($updatePath) === true) {
                $zip->extractTo($tempDir);
                $zip->close();
            } else {
                throw new Exception('无法打开升级包');
            }
            
            // 检查解压后的文件结构
            $sourceDir = $tempDir . '/flos-php';
            if (!file_exists($sourceDir)) {
                throw new Exception('升级包文件结构不正确');
            }
            
            // 递归复制文件
            $this->copyDirectory($sourceDir, APP_ROOT);
            
            // 清理临时文件
            $this->deleteDirectory($tempDir);
            
            // 删除升级包
            if (file_exists($updatePath)) {
                unlink($updatePath);
            }
            
            echo json_encode(['status' => 'success', 'message' => '升级成功']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }
    
    // 递归复制目录
    private function copyDirectory($source, $dest) {
        if (!is_dir($dest)) {
            mkdir($dest, 0777, true);
        }
        
        $files = scandir($source);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $sourcePath = $source . '/' . $file;
                $destPath = $dest . '/' . $file;
                
                if (is_dir($sourcePath)) {
                    $this->copyDirectory($sourcePath, $destPath);
                } else {
                    copy($sourcePath, $destPath);
                }
            }
        }
    }
    
    // 递归删除目录
    private function deleteDirectory($dir) {
        if (!is_dir($dir)) {
            return;
        }
        
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $path = $dir . '/' . $file;
                if (is_dir($path)) {
                    $this->deleteDirectory($path);
                } else {
                    unlink($path);
                }
            }
        }
        
        rmdir($dir);
    }
}
