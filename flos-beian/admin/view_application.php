<?php
session_start();

// 检查是否登录
if (!isset($_SESSION['admin_user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$id = (int)$_GET['id'];

// 引入数据库配置
require_once '../config/database.php';
$config = include '../config/database.php';

// 连接数据库
$database = $config['database'];
$pdo = new PDO("sqlite:$database");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 获取备案信息
$stmt = $pdo->prepare("SELECT * FROM beian_info WHERE id = ?");
$stmt->execute([$id]);
$application = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$application) {
    header('Location: dashboard.php');
    exit;
}

// 处理验证等级更新
$message = '';
if (isset($_POST['update_verification'])) {
    $level = (int)$_POST['verification_level'];
    $stmt = $pdo->prepare("UPDATE beian_info SET verification_level = ? WHERE id = ?");
    $stmt->execute([$level, $id]);
    $message = '验证等级已更新';
    // 刷新数据
    $stmt = $pdo->prepare("SELECT * FROM beian_info WHERE id = ?");
    $stmt->execute([$id]);
    $application = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>查看备案申请 - 管理面板</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .back-link a {
            color: #007cba;
            text-decoration: none;
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
            width: 150px;
        }
        .actions {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .action-btn {
            padding: 8px 15px;
            margin-right: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .approve {
            background: #28a745;
            color: white;
        }
        .reject {
            background: #dc3545;
            color: white;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group select {
            padding: 5px 10px;
        }
        .message {
            padding: 10px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .verification-form {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>查看备案申请详情</h1>
            <div class="back-link"><a href="dashboard.php">← 返回管理面板</a></div>
        </div>
        
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <div class="info-item">
            <span class="info-label">ID：</span>
            <span><?php echo $application['id']; ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">网站名称：</span>
            <span><?php echo htmlspecialchars($application['website_name']); ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">网站域名：</span>
            <span><?php echo htmlspecialchars($application['domain']); ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">网站首页：</span>
            <span><a href="<?php echo htmlspecialchars($application['homepage']); ?>" target="_blank"><?php echo htmlspecialchars($application['homepage']); ?></a></span>
        </div>
        <div class="info-item">
            <span class="info-label">网站信息：</span>
            <span><?php echo htmlspecialchars($application['website_info']); ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">备案号：</span>
            <span><?php echo htmlspecialchars($application['beian_number'] ?: '待验证'); ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">社区备案号：</span>
            <span><?php echo htmlspecialchars($application['community_beian_number'] ?: '-'); ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">社区归属：</span>
            <span><?php echo htmlspecialchars($application['community_belonging'] ?: '-'); ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">所有者：</span>
            <span><?php echo htmlspecialchars($application['owner']); ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">主体：</span>
            <span><?php echo htmlspecialchars($application['subject']); ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">更新时间：</span>
            <span><?php echo date('Y-m-d H:i:s', strtotime($application['updated_time'])); ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">创建时间：</span>
            <span><?php echo date('Y-m-d H:i:s', strtotime($application['created_at'])); ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">状态：</span>
            <span class="<?php echo 'status-' . strtolower(str_replace(' ', '-', $application['status'])); ?>"><?php echo htmlspecialchars($application['status']); ?></span>
        </div>
        <div class="info-item">
            <span class="info-label">主体验证等级：</span>
            <span>
                <?php 
                if ($application['verification_level'] == 0) {
                    echo '<span style="color: red;">未验证</span>';
                } elseif ($application['verification_level'] == 1) {
                    echo '<span style="color: orange;">低风险</span>';
                } elseif ($application['verification_level'] == 2) {
                    echo '<span style="color: yellow;">中风险</span>';
                } else {
                    echo '<span style="color: green;">高风险</span>';
                }
                ?>
            </span>
        </div>
        <div class="info-item">
            <span class="info-label">举报次数：</span>
            <span><?php echo $application['report_count']; ?></span>
        </div>
        
        <div class="actions">
            <?php if ($application['status'] === '待审核'): ?>
                <a href="dashboard.php?action=approve&id=<?php echo $application['id']; ?>" class="action-btn approve">批准备案</a>
                <a href="dashboard.php?action=reject&id=<?php echo $application['id']; ?>" class="action-btn reject">拒绝备案</a>
            <?php elseif ($application['status'] === '已通过'): ?>
                <span>已通过审核</span>
            <?php else: ?>
                <span>已拒绝</span>
            <?php endif; ?>
        </div>
        
        <div class="verification-form">
            <h3>更新主体验证等级</h3>
            <form method="post">
                <div class="form-group">
                    <label for="verification_level">选择验证等级：</label>
                    <select name="verification_level" id="verification_level">
                        <option value="0" <?php echo $application['verification_level'] == 0 ? 'selected' : ''; ?>>未验证</option>
                        <option value="1" <?php echo $application['verification_level'] == 1 ? 'selected' : ''; ?>>低风险</option>
                        <option value="2" <?php echo $application['verification_level'] == 2 ? 'selected' : ''; ?>>中风险</option>
                        <option value="3" <?php echo $application['verification_level'] == 3 ? 'selected' : ''; ?>>高风险</option>
                    </select>
                </div>
                <button type="submit" name="update_verification" class="action-btn" style="background: #007cba; color: white;">更新验证等级</button>
            </form>
        </div>
    </div>
</body>
</html>