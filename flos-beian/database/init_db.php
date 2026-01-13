<?php
// 初始化数据库
$database = '../database/beian.db';

try {
    $pdo = new PDO("sqlite:$database");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 创建用户表
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        email TEXT,
        role TEXT DEFAULT 'user',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);

    // 创建备案信息表
    $sql = "CREATE TABLE IF NOT EXISTS beian_info (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        website_name TEXT NOT NULL,
        domain TEXT NOT NULL,
        homepage TEXT,
        website_info TEXT,
        beian_number TEXT,
        community_beian_number TEXT,
        community_belonging TEXT,
        owner TEXT,
        subject TEXT,
        updated_time DATETIME DEFAULT CURRENT_TIMESTAMP,
        status TEXT DEFAULT '待审核',
        verification_level INTEGER DEFAULT 0,
        report_count INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);

    // 创建管理员账户 (用户名: Admin, 密码: Flos123456)
    $password_hash = password_hash('Flos123456', PASSWORD_DEFAULT);
    $sql = "INSERT OR IGNORE INTO users (username, password, email, role) VALUES ('Admin', '$password_hash', 'admin@example.com', 'admin')";
    $pdo->exec($sql);

    echo "数据库初始化成功！\n";

} catch(PDOException $e) {
    echo "数据库错误: " . $e->getMessage();
}