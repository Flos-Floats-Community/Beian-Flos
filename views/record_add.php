<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>提交备案 - 浮云社 ICP备案站点</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
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
            max-width: 800px;
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
        input[type="url"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        textarea {
            height: 100px;
            resize: vertical;
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
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        .tips {
            background-color: #f8f9fa;
            padding: 10px;
            border-left: 4px solid #007bff;
            margin-bottom: 20px;
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
        <h2>提交备案</h2>
        <div class="tips">
            <p>提示：</p>
            <ul>
                <li>主体若为公司，需要验证主体的营业执照等</li>
                <li>不允许选号，验证前不会显示备案号</li>
                <li>提交后请等待管理员审核</li>
            </ul>
        </div>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="/record/save" method="post">
            <div class="form-group">
                <label for="site_name">网站名称 <span style="color: red;">*</span></label>
                <input type="text" id="site_name" name="site_name" required>
            </div>
            <div class="form-group">
                <label for="domain">网站域名 <span style="color: red;">*</span></label>
                <input type="text" id="domain" name="domain" required>
            </div>
            <div class="form-group">
                <label for="homepage">网站首页 <span style="color: red;">*</span></label>
                <input type="url" id="homepage" name="homepage" required>
            </div>
            <div class="form-group">
                <label for="site_info">网站信息 <span style="color: red;">*</span></label>
                <textarea id="site_info" name="site_info" required></textarea>
            </div>
            <div class="form-group">
                <label for="other_icp_number">国家/其他备案社区备案号（选填）</label>
                <input type="text" id="other_icp_number" name="other_icp_number">
            </div>
            <div class="form-group">
                <label for="icp_community">备案社区归属（选填）</label>
                <input type="text" id="icp_community" name="icp_community">
            </div>
            <div class="form-group">
                <label for="owner">所有者 <span style="color: red;">*</span></label>
                <input type="text" id="owner" name="owner" required>
            </div>
            <div class="form-group">
                <label for="subject">主体 <span style="color: red;">*</span></label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <button type="submit">提交备案</button>
        </form>
    </div>
    
    <div class="footer">
        <p>Copyright Float Studio/Flos Float Community/XuanYi Cloud 2018~2026. All rights Reserved.</p>
    </div>
</body>
</html>
