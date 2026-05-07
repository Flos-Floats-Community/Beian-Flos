<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理员后台 - 浮云社 ICP备案站点</title>
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
        <h2>待审核备案记录</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>网站名称</th>
                        <th>网站域名</th>
                        <th>网站首页</th>
                        <th>所有者</th>
                        <th>主体</th>
                        <th>提交时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record['site_name']); ?></td>
                            <td><?php echo htmlspecialchars($record['domain']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($record['homepage']); ?>" target="_blank"><?php echo htmlspecialchars($record['homepage']); ?></a></td>
                            <td><?php echo htmlspecialchars($record['owner']); ?></td>
                            <td><?php echo htmlspecialchars($record['subject']); ?></td>
                            <td><?php echo htmlspecialchars($record['create_time']); ?></td>
                            <td>
                                <a href="/admin/verify/<?php echo $record['id']; ?>" class="btn btn-success">通过</a>
                                <a href="/admin/reject/<?php echo $record['id']; ?>" class="btn btn-danger">拒绝</a>
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
    
    <!-- 升级提示模态框 -->
    <div id="updateModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 9999;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #fff; padding: 30px; border-radius: 10px; width: 90%; max-width: 500px;">
            <h3 style="margin-bottom: 20px; color: #333;">发现新版本</h3>
            <p style="margin-bottom: 30px; color: #666;">检测到有新的系统升级包，是否立即更新？</p>
            <p style="font-size: 12px; color: #999; margin-bottom: 20px;">更新将会覆盖现有文件，请确保已备份重要数据。</p>
            <div style="text-align: right;">
                <button id="cancelUpdate" style="padding: 10px 20px; background-color: #6c757d; color: #fff; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px;">取消</button>
                <button id="confirmUpdate" style="padding: 10px 20px; background-color: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer;">同意更新</button>
            </div>
        </div>
    </div>
    
    <!-- 升级进度模态框 -->
    <div id="upgradeProgressModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 9999;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #fff; padding: 30px; border-radius: 10px; width: 90%; max-width: 500px;">
            <h3 style="margin-bottom: 20px; color: #333;">系统更新中</h3>
            <div style="margin-bottom: 20px;">
                <div style="width: 100%; height: 20px; background-color: #f0f0f0; border-radius: 10px; overflow: hidden;">
                    <div id="progressBar" style="width: 0%; height: 100%; background-color: #007bff; transition: width 0.3s;"></div>
                </div>
                <p id="progressText" style="text-align: center; margin-top: 10px; color: #666;">准备更新...</p>
            </div>
        </div>
    </div>
    
    <script>
        // 设置随机背景图片
        function setRandomBackground() {
            var bgUrl = 'https://www.loliapi.com/bg/';
            document.body.style.backgroundImage = 'url(' + bgUrl + ')';
        }
        
        // 检查更新
        function checkUpdate() {
            fetch('/admin/checkUpdate')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success' && data.hasUpdate) {
                        document.getElementById('updateModal').style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('检查更新失败:', error);
                });
        }
        
        // 取消更新
        document.getElementById('cancelUpdate').addEventListener('click', function() {
            document.getElementById('updateModal').style.display = 'none';
        });
        
        // 确认更新
        document.getElementById('confirmUpdate').addEventListener('click', function() {
            document.getElementById('updateModal').style.display = 'none';
            document.getElementById('upgradeProgressModal').style.display = 'block';
            
            // 模拟进度
            let progress = 0;
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');
            
            const interval = setInterval(function() {
                progress += 10;
                if (progress > 90) {
                    clearInterval(interval);
                }
                progressBar.style.width = progress + '%';
                progressText.textContent = '更新中... ' + progress + '%';
            }, 200);
            
            // 执行更新
            fetch('/admin/upgrade')
                .then(response => response.json())
                .then(data => {
                    clearInterval(interval);
                    if (data.status === 'success') {
                        progressBar.style.width = '100%';
                        progressText.textContent = '更新完成！';
                        setTimeout(function() {
                            document.getElementById('upgradeProgressModal').style.display = 'none';
                            alert('系统更新成功！');
                            location.reload();
                        }, 1000);
                    } else {
                        progressText.textContent = '更新失败: ' + data.message;
                        setTimeout(function() {
                            document.getElementById('upgradeProgressModal').style.display = 'none';
                            alert('更新失败: ' + data.message);
                        }, 2000);
                    }
                })
                .catch(error => {
                    clearInterval(interval);
                    progressText.textContent = '更新失败: 网络错误';
                    setTimeout(function() {
                        document.getElementById('upgradeProgressModal').style.display = 'none';
                        alert('更新失败: 网络错误');
                    }, 2000);
                });
        });
        
        // 页面加载时检查更新和设置背景
        window.onload = function() {
            setRandomBackground();
            checkUpdate();
        };
        
        // 窗口大小改变时重新设置背景
        window.onresize = setRandomBackground;
    </script>
</body>
</html>
