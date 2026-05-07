<?php
// 数据库初始化脚本
$dbPath = __DIR__ . '/flos_php.db';

// 连接SQLite数据库
$db = new PDO('sqlite:' . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 创建用户表
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role INTEGER DEFAULT 0, -- 0 普通用户，1 管理员
    create_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    update_time DATETIME DEFAULT CURRENT_TIMESTAMP
)");

// 创建备案记录表
$db->exec("CREATE TABLE IF NOT EXISTS icp_records (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    site_name VARCHAR(100) NOT NULL,
    domain VARCHAR(100) NOT NULL,
    homepage VARCHAR(255) NOT NULL,
    site_info TEXT NOT NULL,
    icp_number VARCHAR(50),
    other_icp_number VARCHAR(100),
    icp_community VARCHAR(100),
    owner VARCHAR(100) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    subject_verified INTEGER DEFAULT 0,
    status INTEGER DEFAULT 0, -- 0 待审核，1 已通过，2 已拒绝
    create_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    update_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)");

// 生成默认管理员密码哈希
$passwordHash = password_hash('Flos123456', PASSWORD_DEFAULT);

// 添加默认管理员账户
$stmt = $db->prepare("INSERT OR IGNORE INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
$stmt->execute(['Admin', $passwordHash, 'admin@example.com', 1]);

echo "数据库初始化成功！\n";
echo "默认管理员账号：Admin\n";
echo "默认管理员密码：Flos123456\n";
