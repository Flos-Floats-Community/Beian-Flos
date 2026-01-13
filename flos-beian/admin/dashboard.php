<?php
session_start();

// 检查是否登录
if (!isset($_SESSION['admin_user_id'])) {
    header('Location: login.php');
    exit;
}

// 引入数据库配置
require_once '../config/database.php';
$config = include '../config/database.php';

// 连接数据库
$database = $config['database'];
$pdo = new PDO("sqlite:$database");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 统计数据
$stmt = $pdo->query("SELECT COUNT(*) FROM beian_info");
$total_sites = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM beian_info WHERE status = '待审核'");
$pending_sites = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM beian_info WHERE status = '已通过'");
$approved_sites = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM beian_info WHERE report_count > 0");
$reported_sites = $stmt->fetchColumn();

// 获取最新的备案申请
$stmt = $pdo->query("SELECT * FROM beian_info ORDER BY created_at DESC LIMIT 10");
$recent_applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 获取被举报的备案信息
$stmt = $pdo->query("SELECT * FROM beian_info WHERE report_count > 0 ORDER BY report_count DESC LIMIT 10");
$reported_applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 处理操作请求
$message = '';
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = (int)$_GET['id'];
    
    if ($action === 'approve') {
        // 生成备案号（这里简化处理，实际应用中可能有更复杂的规则）
        $beian_number = '浮ICP备' . str_pad($id, 6, '0', STR_PAD_LEFT) . '号';
        $stmt = $pdo->prepare("UPDATE beian_info SET status = '已通过', beian_number = ? WHERE id = ?");
        $stmt->execute([$beian_number, $id]);
        $message = '备案已批准';
    } elseif ($action === 'reject') {
        $stmt = $pdo->prepare("UPDATE beian_info SET status = '已拒绝' WHERE id = ?");
        $stmt->execute([$id]);
        $message = '备案已拒绝';
    } elseif ($action === 'verify') {
        $level = (int)$_GET['level'];
        $stmt = $pdo->prepare("UPDATE beian_info SET verification_level = ? WHERE id = ?");
        $stmt->execute([$level, $id]);
        $message = '验证等级已更新';
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理面板 - 浮云社 ICP备案管理系统</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .header {
            background: #333;
            color: white;
            padding: 15px 20px;
            position: relative;
        }
        .header h1 {
            margin: 0;
            font-size: 1.5em;
            display: inline-block;
        }
        .logout {
            position: absolute;
            right: 20px;
            top: 15px;
        }
        .logout a {
            color: white;
            text-decoration: none;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #007cba;
        }
        .stat-label {
            margin-top: 10px;
            color: #666;
        }
        .section {
            background: white;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .section-header {
            background: #007cba;
            color: white;
            padding: 15px 20px;
            margin: 0;
        }
        .section-content {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
        }
        .actions a {
            margin-right: 10px;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 3px;
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
        .verify-low {
            background: #ffc107;
            color: black;
        }
        .verify-medium {
            background: #fd7e14;
            color: white;
        }
        .verify-high {
            background: #6f42c1;
            color: white;
        }
        .message {
            padding: 10px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .status-pending { color: orange; }
        .status-approved { color: green; }
        .status-rejected { color: red; }
    </style>
</head>
<body>
    <div class="header">
        <h1>浮云社 ICP备案管理系统 - 管理面板</h1>
        <div class="logout"><a href="logout.php">退出</a></div>
    </div>
    
    <div class="container">
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_sites; ?></div>
                <div class="stat-label">备案总数</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $pending_sites; ?></div>
                <div class="stat-label">待审核</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $approved_sites; ?></div>
                <div class="stat-label">已通过</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $reported_sites; ?></div>
                <div class="stat-label">被举报</div>
            </div>
        </div>
        
        <div class="section">
            <h2 class="section-header">最新备案申请</h2>
            <div class="section-content">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>网站名称</th>
                            <th>网站域名</th>
                            <th>所有者</th>
                            <th>提交时间</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_applications as $app): ?>
                        <tr>
                            <td><?php echo $app['id']; ?></td>
                            <td><?php echo htmlspecialchars($app['website_name']); ?></td>
                            <td><?php echo htmlspecialchars($app['domain']); ?></td>
                            <td><?php echo htmlspecialchars($app['owner']); ?></td>
                            <td><?php echo date('Y-m-d H:i:s', strtotime($app['created_at'])); ?></td>
                            <td class="status-<?php echo strtolower(str_replace(' ', '-', $app['status'])); ?>"><?php echo htmlspecialchars($app['status']); ?></td>
                            <td class="actions">
                                <?php if ($app['status'] === '待审核'): ?>
                                    <a href="?action=approve&id=<?php echo $app['id']; ?>" class="approve">批准</a>
                                    <a href="?action=reject&id=<?php echo $app['id']; ?>" class="reject">拒绝</a>
                                <?php endif; ?>
                                <a href="view_application.php?id=<?php echo $app['id']; ?>">查看</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="section">
            <h2 class="section-header">被举报的备案</h2>
            <div class="section-content">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>网站名称</th>
                            <th>举报次数</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reported_applications as $app): ?>
                        <tr>
                            <td><?php echo $app['id']; ?></td>
                            <td><?php echo htmlspecialchars($app['website_name']); ?></td>
                            <td><?php echo $app['report_count']; ?></td>
                            <td class="status-<?php echo strtolower(str_replace(' ', '-', $app['status'])); ?>"><?php echo htmlspecialchars($app['status']); ?></td>
                            <td class="actions">
                                <a href="view_application.php?id=<?php echo $app['id']; ?>">查看</a>
                                <a href="?action=approve&id=<?php echo $app['id']; ?>" class="approve">批准</a>
                                <a href="?action=reject&id=<?php echo $app['id']; ?>" class="reject">拒绝</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>