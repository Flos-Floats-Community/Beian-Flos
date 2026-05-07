<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>编辑用户 - 浮云社 ICP备案站点</title>
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
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        h2 {
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        button {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        button:hover {
            background-color: #555;
        }
        
        .btn-primary {
            background-color: #007bff;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            margin-left: 10px;
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
        <h1>浮云社 ICP备案站点 - 管理员后台</h1>
    </div>
    
    <div class="nav">
        <a href="/">返回首页</a>
        <a href="/admin/flos">审核备案</a>
        <a href="/admin/users">用户管理</a>
        <a href="/login/logout">退出</a>
    </div>
    
    <div class="container">
        <h2>编辑用户</h2>
        <form action="/admin/editUser/<?php echo $user['id']; ?>" method="post">
            <div class="form-group">
                <label for="username">用户名</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">邮箱</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="role">角色</label>
                <select id="role" name="role">
                    <option value="0" <?php echo $user['role'] == 0 ? 'selected' : ''; ?>>普通用户</option>
                    <option value="1" <?php echo $user['role'] == 1 ? 'selected' : ''; ?>>管理员</option>
                </select>
            </div>
            <button type="submit" class="btn-primary">保存</button>
            <a href="/admin/users" class="btn-secondary" style="display: inline-block; padding: 10px 20px; text-decoration: none; border-radius: 4px;">取消</a>
        </form>
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
