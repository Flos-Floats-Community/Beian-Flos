<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';
$success = false;

if (isset($_GET['id'])) {
    $record_id = (int)$_GET['id'];
    
    // 引入数据库配置
    require_once '../config/database.php';
    $config = include '../config/database.php';
    
    // 连接数据库
    $database = $config['database'];
    $pdo = new PDO("sqlite:$database");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 增加举报计数
        $stmt = $pdo->prepare("UPDATE beian_info SET report_count = report_count + 1 WHERE id = ?");
        $result = $stmt->execute([$record_id]);
        
        if ($result) {
            $success = true;
            $message = '举报成功，我们会尽快处理。';
        } else {
            $message = '举报失败，请稍后重试。';
        }
    }
    
    // 获取备案信息
    $stmt = $pdo->prepare("SELECT * FROM beian_info WHERE id = ?");
    $stmt->execute([$record_id]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$record) {
        header('Location: index.php');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>举报备案信息 - 浮云社 ICP备案系统</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .info-item {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 120px;
        }
        .btn {
            padding: 10px 20px;
            background: #d9534f;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        .btn:hover {
            background: #c9302c;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .message {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .back-link {
            margin-top: 20px;
            text-align: center;
        }
        .back-link a {
            color: #007cba;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>举报备案信息</h1>
        </div>
        
        <?php if ($success && !empty($message)): ?>
            <div class="message success"><?php echo htmlspecialchars($message); ?></div>
        <?php elseif (!empty($message)): ?>
            <div class="message error" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if (!$success): ?>
        <h3>备案信息详情：</h3>
        <div class="info-item">
            <span class="info-label">网站名称：</span>
            <span><?php echo htmlspecialchars($record['website_name']); ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">网站域名：</span>
            <span><?php echo htmlspecialchars($record['domain']); ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">网站首页：</span>
            <span><?php echo htmlspecialchars($record['homepage']); ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">网站信息：</span>
            <span><?php echo htmlspecialchars($record['website_info']); ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">所有者：</span>
            <span><?php echo htmlspecialchars($record['owner']); ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">主体：</span>
            <span><?php echo htmlspecialchars($record['subject']); ?></span>
        </div>
        
        <form method="post" style="margin-top: 30px;">
            <p>您确定要举报这条备案信息吗？举报后我们将进行核实处理。</p>
            <button type="submit" class="btn">确认举报</button>
            <a href="index.php" class="btn btn-secondary">取消</a>
        </form>
        <?php else: ?>
            <div class="back-link">
                <a href="index.php">返回首页</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>