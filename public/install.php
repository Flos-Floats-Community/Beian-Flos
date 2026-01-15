<?php
header('Content-Type: text/html; charset=utf-8');
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
$dbFile = ROOT_PATH . 'runtime/database.db';

if (!is_dir(ROOT_PATH . 'runtime')) {
    mkdir(ROOT_PATH . 'runtime', 0755, true);
}

$needInstall = !file_exists($dbFile);

if ($_POST['do'] === 'install') {
    try {
        $pdo = new PDO("sqlite:$dbFile");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 用户表
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE NOT NULL,
                email TEXT,
                password TEXT NOT NULL,
                created_at TEXT
            );
        ");

        // 备案记录表
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS records (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                site_name TEXT NOT NULL,
                domain TEXT NOT NULL UNIQUE,
                homepage TEXT,
                info TEXT,
                float_icp TEXT,
                national_icp TEXT,
                community TEXT,
                owner TEXT NOT NULL,
                entity TEXT NOT NULL,
                updated_at TEXT,
                status TEXT DEFAULT 'pending',
                entity_verified INTEGER DEFAULT 0,
                report_url TEXT
            );
        ");

        // 管理员表
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS admins (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE NOT NULL,
                password TEXT NOT NULL
            );
        ");

        // 默认管理员
        $adminUser = $_POST['username'] ?: 'Admin';
        $adminPass = $_POST['password'] ?: 'Flos123456';
        $hash = password_hash($adminPass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT OR IGNORE INTO admins (username, password) VALUES (?, ?)");
        $stmt->execute([$adminUser, $hash]);

        echo "<h2>✅ 安装成功！</h2>";
        echo "<p>数据库已初始化，管理员账号已创建。</p>";
        echo "<p>用户名：<strong>" . htmlspecialchars($adminUser) . "</strong></p>";
        echo "<p>密码：<strong>" . htmlspecialchars($adminPass) . "</strong></p>";
        echo "<p>请 <a href='/'>返回首页</a> 或 <a href='/admin/flos/login'>进入管理后台</a>。</p>";
        echo "<p style='color:red;'>⚠️ 建议立即删除 install.php 文件以确保安全！</p>";
        exit;
    } catch (Exception $e) {
        die("<h2>❌ 安装失败</h2><pre>" . htmlspecialchars($e->getMessage()) . "</pre>");
    }
}

// 检查是否已安装
if (!$needInstall && file_exists($dbFile)) {
    try {
        $pdo = new PDO("sqlite:$dbFile");
        $pdo->query("SELECT 1 FROM admins LIMIT 1");
        echo "<h2>⚠️ 系统已初始化</h2>";
        echo "<p>无需重复安装。如需重装，请删除 <code>runtime/database.db</code> 后刷新本页。</p>";
        echo "<p><a href='/'>返回首页</a></p>";
        exit;
    } catch (Exception $e) {
        $needInstall = true;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>浮云社 ICP 备案系统 - 安装向导</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; background: #f9f9f9; }
        input, button { padding: 10px; margin: 8px 0; width: 100%; box-sizing: border-box; }
        h1 { text-align: center; color: #333; }
        .note { color: #666; font-size: 0.9em; margin-top: 20px; }
        code { background: #eee; padding: 2px 4px; }
    </style>
</head>
<body>
    <h1>浮云社 ICP 备案系统安装</h1>
    <?php if ($needInstall): ?>
    <form method="post">
        <input type="hidden" name="do" value="install">
        <label>管理员用户名</label>
        <input type="text" name="username" value="Admin" required>
        <label>管理员密码</label>
        <input type="password" name="password" value="Flos123456" required>
        <button type="submit">🚀 立即安装</button>
        <p class="note">系统将自动创建 SQLite 数据库并初始化表结构。</p>
    </form>
    <?php else: ?>
    <p>系统已安装。请删除本文件以确保安全。</p>
    <?php endif; ?>
</body>
</html>