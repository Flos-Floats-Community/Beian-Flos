<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>我的备案 - 浮云社 ICP备案站点</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            color: #333;
            min-height: 100vh;
            position: relative;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: -1;
        }
        
        .header {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .header h1 {
            text-align: center;
            color: #333;
        }
        
        .nav {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
        
        .nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }
        
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        h2 {
            margin-bottom: 20px;
        }
        
        .table-container {
            overflow-x: auto;
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
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        .status-approved {
            color: green;
        }
        
        .status-pending {
            color: orange;
        }
        
        .status-rejected {
            color: red;
        }
        
        .footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>浮云社 ICP备案站点</h1>
    </div>
    
    <div class="nav">
        <a href="/">首页</a>
        <a href="/record/add">提交备案</a>
        <a href="/record/my">我的备案</a>
        <a href="/login/logout">退出</a>
    </div>
    
    <div class="container">
        <h2>我的备案记录</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>网站名称</th>
                        <th>网站域名</th>
                        <th>网站首页</th>
                        <th>浮备案号</th>
                        <th>状态</th>
                        <th>提交时间</th>
                        <th>更新时间</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record['site_name']); ?></td>
                            <td><?php echo htmlspecialchars($record['domain']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($record['homepage']); ?>" target="_blank"><?php echo htmlspecialchars($record['homepage']); ?></a></td>
                            <td><?php echo htmlspecialchars($record['icp_number'] ?? ''); ?></td>
                            <td>
                                <?php 
                                switch ($record['status']) {
                                    case 0:
                                        echo '<span class="status-pending">待审核</span>';
                                        break;
                                    case 1:
                                        echo '<span class="status-approved">已通过</span>';
                                        break;
                                    case 2:
                                        echo '<span class="status-rejected">已拒绝</span>';
                                        break;
                                }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($record['create_time']); ?></td>
                            <td><?php echo htmlspecialchars($record['update_time']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="footer">
        <p>Copyright Float Studio/Flos Float Community/XuanYi Cloud 2018~2026. All rights Reserved.</p>
    </div>
    
    <script>
        // 设置随机背景图片
        function setRandomBackground() {
            var bgUrl = 'https://www.loliapi.com/bg/';
            document.body.style.backgroundImage = 'url(' + bgUrl + ')';
        }

        // 页面加载时设置背景
        window.onload = function() {
            setRandomBackground();
        };

        // 窗口大小改变时重新设置背景
        window.onresize = function() {
            setRandomBackground();
        };
    </script>
</body>
</html>
