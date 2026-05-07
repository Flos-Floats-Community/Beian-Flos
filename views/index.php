<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>浮云社 ICP备案站点</title>
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
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            min-height: calc(100vh - 120px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .logo {
            font-size: 36px;
            font-weight: bold;
            color: #333;
            margin-bottom: 40px;
            text-align: center;
        }
        
        .search-box {
            width: 100%;
            max-width: 600px;
            margin-bottom: 40px;
        }
        
        .search-input {
            width: 100%;
            padding: 15px 20px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 30px;
            outline: none;
            transition: border-color 0.3s;
        }
        
        .search-input:focus {
            border-color: #007bff;
        }
        
        .search-btn {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.3s;
        }
        
        .search-btn:hover {
            background-color: #0069d9;
        }
        
        .nav {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 15px;
            text-align: center;
        }
        
        .nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 14px;
            transition: color 0.3s;
        }
        
        .nav a:hover {
            color: #007bff;
        }
        
        .record-list {
            width: 100%;
            max-width: 800px;
            margin-top: 40px;
        }
        
        .record-item {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .record-item h3 {
            margin-bottom: 15px;
            color: #007bff;
        }
        
        .record-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 14px;
            color: #333;
        }
        
        .info-value a {
            color: #007bff;
            text-decoration: none;
        }
        
        .info-value a:hover {
            text-decoration: underline;
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
        
        .btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
            font-size: 12px;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #555;
        }
        
        .btn-danger {
            background-color: #dc3545;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
        }
        
        .footer {
            position: absolute;
            bottom: 60px;
            left: 0;
            right: 0;
            text-align: center;
            color: #666;
            font-size: 12px;
            padding: 10px;
        }
        
        /* 竖排显示备案信息 - 响应式 */
        @media (max-width: 768px) {
            .record-info {
                grid-template-columns: 1fr;
            }
            
            .info-item {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                padding: 5px 0;
                border-bottom: 1px solid #f0f0f0;
            }
            
            .info-label {
                margin-bottom: 0;
                font-weight: bold;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">浮云社 ICP备案站点</div>
        
        <div class="search-box">
            <input type="text" class="search-input" placeholder="搜索网站名称、域名或备案号...">
            <button class="search-btn">搜索</button>
        </div>
        
        <?php if (!empty($records)): ?>
            <div class="record-list">
                <?php foreach ($records as $record): ?>
                    <div class="record-item">
                        <h3><?php echo htmlspecialchars($record['site_name']); ?></h3>
                        <div class="record-info">
                            <div class="info-item">
                                <span class="info-label">网站域名</span>
                                <span class="info-value"><?php echo htmlspecialchars($record['domain']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">网站首页</span>
                                <span class="info-value"><a href="<?php echo htmlspecialchars($record['homepage']); ?>" target="_blank"><?php echo htmlspecialchars($record['homepage']); ?></a></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">网站信息</span>
                                <span class="info-value"><?php echo htmlspecialchars($record['site_info']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">浮备案号</span>
                                <span class="info-value"><?php echo htmlspecialchars($record['icp_number'] ?? ''); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">国家/其他备案社区备案号</span>
                                <span class="info-value"><?php echo htmlspecialchars($record['other_icp_number'] ?? ''); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">备案社区归属</span>
                                <span class="info-value"><?php echo htmlspecialchars($record['icp_community'] ?? ''); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">所有者</span>
                                <span class="info-value"><?php echo htmlspecialchars($record['owner']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">主体</span>
                                <span class="info-value"><?php echo htmlspecialchars($record['subject']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">更新时间</span>
                                <span class="info-value"><?php echo htmlspecialchars($record['update_time']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">状态</span>
                                <span class="info-value">
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
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">是否可以验证主体</span>
                                <span class="info-value"><?php echo $record['subject_verified'] ? '是' : '否'; ?></span>
                            </div>
                        </div>
                        <a href="#" class="btn btn-danger" onclick="reportRecord(<?php echo $record['id']; ?>)">举报</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; margin-top: 40px; color: #666;">
                暂无备案记录
            </div>
        <?php endif; ?>
    </div>
    
    <div class="footer">
        <p>Copyright Float Studio/Flos Float Community/XuanYi Cloud 2018~2026. All rights Reserved.</p>
    </div>
    
    <div class="nav">
        <a href="/">首页</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/record/add">提交备案</a>
            <a href="/record/my">我的备案</a>
            <a href="/login/logout">退出</a>
        <?php else: ?>
            <a href="/login">登录/注册</a>
        <?php endif; ?>
        <a href="/admin/flos">管理登录</a>
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
        
        // 举报功能
        function reportRecord(id) {
            if (confirm('确定要举报此备案记录吗？')) {
                // 这里可以添加AJAX举报逻辑
                alert('举报已提交，管理员将尽快处理');
            }
        }
    </script>
</body>
</html>
