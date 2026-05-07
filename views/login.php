<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登录 - 浮云社 ICP备案站点</title>
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
        
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 4px;
        }
        
        h2 {
            text-align: center;
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
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        button {
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        button:hover {
            background-color: #555;
        }
        
        .link {
            text-align: center;
            margin-top: 15px;
        }
        
        .link a {
            color: #007bff;
            text-decoration: none;
        }
        
        .link a:hover {
            text-decoration: underline;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        .footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>浮云社 ICP备案站点</h1>
    </div>
    
    <div class="container">
        <h2>登录</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="/login/doLogin" method="post">
            <div class="form-group">
                <label for="username">用户名</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">密码</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">登录</button>
            <div class="link">
                <a href="/login/register">注册新账号</a>
            </div>
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
