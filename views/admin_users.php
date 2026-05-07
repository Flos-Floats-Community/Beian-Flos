<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户管理 - 浮云社 ICP备案站点</title>
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
        
        .btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 5px;
        }
        
        .btn:hover {
            background-color: #555;
        }
        
        .btn-primary {
            background-color: #007bff;
        }
        
        .btn-success {
            background-color: #28a745;
        }
        
        .btn-danger {
            background-color: #dc3545;
        }
        
        .footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px;
            margin-top: 20px;
        }
        
        .role-admin {
            color: #dc3545;
            font-weight: bold;
        }
        
        .role-user {
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>浮云社 ICP备案站点 - 管理员后台</h1>
    </div>
    
    <div class="nav">
        <a href="/">返回首页</a>
        <a href="/admin/flos">审核备案</a>
        <a href="/admin/users">用户管理</a>
        <a href="/login/logout">退出</a>
    </div>
    
    <div class="container">
        <h2>用户管理</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>用户名</th>
                        <th>邮箱</th>
                        <th>角色</th>
                        <th>创建时间</th>
                        <th>更新时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <?php if ($user['role'] == 1): ?>
                                    <span class="role-admin">管理员</span>
                                <?php else: ?>
                                    <span class="role-user">普通用户</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $user['create_time']; ?></td>
                            <td><?php echo $user['update_time']; ?></td>
                            <td>
                                <a href="/admin/editUser/<?php echo $user['id']; ?>" class="btn btn-primary">编辑</a>
                                <?php if ($user['id'] != 1): ?>
                                    <a href="/admin/deleteUser/<?php echo $user['id']; ?>" class="btn btn-danger" onclick="return confirm('确定要删除此用户吗？');">删除</a>
                                <?php endif; ?>
                            </td>
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
