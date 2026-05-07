<?php
// 用户模型类
require_once __DIR__ . '/Database.php';

class User {
    private $id;
    private $username;
    private $password;
    private $email;
    private $role;
    private $create_time;
    private $update_time;
    
    // 注册用户
    public static function register($username, $password, $email) {
        // 检查用户名是否已存在
        $stmt = Database::query('SELECT id FROM users WHERE username = ?', [$username]);
        if ($stmt->rowCount() > 0) {
            return false;
        }
        
        // 检查邮箱是否已存在
        $stmt = Database::query('SELECT id FROM users WHERE email = ?', [$email]);
        if ($stmt->rowCount() > 0) {
            return false;
        }
        
        // 加密密码
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        // 插入用户
        $stmt = Database::query(
            'INSERT INTO users (username, password, email, role, create_time, update_time) VALUES (?, ?, ?, 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)',
            [$username, $passwordHash, $email]
        );
        
        return $stmt->rowCount() > 0;
    }
    
    // 用户登录
    public static function login($username, $password) {
        $stmt = Database::query('SELECT * FROM users WHERE username = ?', [$username]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return false;
        }
        
        if (!password_verify($password, $user['password'])) {
            return false;
        }
        
        // 保存用户信息到会话
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        return true;
    }
    
    // 获取当前登录用户
    public static function getCurrentUser() {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        
        $stmt = Database::query('SELECT * FROM users WHERE id = ?', [$_SESSION['user_id']]);
        return $stmt->fetch();
    }
    
    // 检查是否为管理员
    public static function isAdmin() {
        $user = self::getCurrentUser();
        return $user && $user['role'] == 1;
    }
    
    // 退出登录
    public static function logout() {
        session_destroy();
    }
}
