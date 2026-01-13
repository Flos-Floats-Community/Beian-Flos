<?php
// 公共首页
session_start();

// 引入数据库配置
require_once '../config/database.php';
$config = include '../config/database.php';

// 连接数据库
$database = $config['database'];
$pdo = new PDO("sqlite:$database");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 获取查询参数
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// 构建查询语句
$where_clause = "";
$params = [];
if (!empty($search)) {
    $where_clause = "WHERE website_name LIKE :search OR domain LIKE :search OR subject LIKE :search";
    $params[':search'] = "%{$search}%";
}

// 查询总记录数
$count_sql = "SELECT COUNT(*) FROM beian_info {$where_clause}";
$count_stmt = $pdo->prepare($count_sql);
foreach ($params as $key => $value) {
    $count_stmt->bindValue($key, $value);
}
$count_stmt->execute();
$total_records = $count_stmt->fetchColumn();
$total_pages = ceil($total_records / $limit);

// 查询备案信息
$sql = "SELECT * FROM beian_info {$where_clause} ORDER BY updated_time DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>浮云社 ICP备案查询系统</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
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
        .header h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .search-box {
            margin-bottom: 20px;
            text-align: center;
        }
        .search-box input {
            padding: 10px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .search-box button {
            padding: 10px 20px;
            background: #007cba;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-left: 10px;
        }
        .search-box button:hover {
            background: #005a87;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 4px;
            text-decoration: none;
            border: 1px solid #ddd;
            color: #007cba;
        }
        .pagination a.active {
            background-color: #007cba;
            color: white;
        }
        .pagination a:hover {
            background-color: #ddd;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
        }
        .login-link {
            float: right;
        }
        .status-pending { color: orange; }
        .status-approved { color: green; }
        .status-rejected { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>浮云社 ICP备案查询系统</h1>
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="login-link">欢迎, <?php echo htmlspecialchars($_SESSION['username']); ?> | <a href="logout.php">退出</a></div>
            <?php else: ?>
                <div class="login-link"><a href="login.php">登录</a> | <a href="register.php">注册</a></div>
            <?php endif; ?>
        </div>
        
        <div class="search-box">
            <form method="get">
                <input type="text" name="search" placeholder="搜索网站名称、域名或主体..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">搜索</button>
            </form>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>网站名称</th>
                    <th>网站域名</th>
                    <th>网站首页</th>
                    <th>网站信息</th>
                    <th>备案号</th>
                    <th>社区备案号</th>
                    <th>社区归属</th>
                    <th>所有者</th>
                    <th>主体</th>
                    <th>更新时间</th>
                    <th>状态</th>
                    <th>主体验证</th>
                    <th>举报</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['website_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['domain']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($row['homepage']); ?>" target="_blank">访问</a></td>
                    <td><?php echo htmlspecialchars(substr($row['website_info'], 0, 50)) . (strlen($row['website_info']) > 50 ? '...' : ''); ?></td>
                    <td><?php echo htmlspecialchars($row['beian_number'] ?: '待验证'); ?></td>
                    <td><?php echo htmlspecialchars($row['community_beian_number'] ?: '-'); ?></td>
                    <td><?php echo htmlspecialchars($row['community_belonging'] ?: '-'); ?></td>
                    <td><?php echo htmlspecialchars($row['owner']); ?></td>
                    <td><?php echo htmlspecialchars($row['subject']); ?></td>
                    <td><?php echo date('Y-m-d H:i:s', strtotime($row['updated_time'])); ?></td>
                    <td class="status-<?php echo strtolower(str_replace(' ', '-', $row['status'])); ?>"><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <?php 
                        if ($row['verification_level'] == 0) {
                            echo '<span style="color: red;">未验证</span>';
                        } elseif ($row['verification_level'] == 1) {
                            echo '<span style="color: orange;">低风险</span>';
                        } elseif ($row['verification_level'] == 2) {
                            echo '<span style="color: yellow;">中风险</span>';
                        } else {
                            echo '<span style="color: green;">高风险</span>';
                        }
                        ?>
                    </td>
                    <td><a href="report.php?id=<?php echo $row['id']; ?>">举报</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- 分页 -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">&laquo; 上一页</a>
            <?php endif; ?>
            
            <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" <?php if ($i == $page) echo 'class="active"'; ?>><?php echo $i; ?></a>
            <?php endfor; ?>
            
            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">下一页 &raquo;</a>
            <?php endif; ?>
        </div>
        
        <div class="footer">
            <p>版权信息：Copyright Float Studio/Flos Float Community 2018~2026. All rights Reserved.</p>
        </div>
    </div>
</body>
</html>