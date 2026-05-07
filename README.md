# 浮云社 ICP备案站点（原生PHP版）

## 系统介绍

这是一个基于原生PHP开发的ICP备案管理系统，类似于 `https://icp.gov.moe/`，但具有不同的表格内容和功能。

## 系统功能

1. **用户功能**
   - 注册/登录
   - 提交备案申请
   - 查看我的备案记录

2. **管理员功能**
   - 登录管理后台（/admin/flos）
   - 审核备案申请
   - 验证主体信息

3. **备案管理**
   - 不允许选号
   - 验证前不会显示备案号
   - 主体若为公司，需要验证主体的营业执照等

## 技术栈

- PHP 7.1+
- SQLite 3
- HTML5 + CSS3
- Apache/Nginx（支持URL重写）

## 目录结构

```
├── config/           # 配置文件
├── controllers/      # 控制器
├── models/           # 模型
├── views/            # 视图
├── database/         # 数据库
│   ├── init.php      # 数据库初始化脚本
│   └── flos_icp.db   # SQLite数据库文件
├── public/           # 公共目录
│   ├── index.php     # 入口文件
│   └── .htaccess     # Apache重写规则
└── README.md         # 说明文档
```

## 部署步骤

1. **环境准备**
   - 安装PHP 7.1或更高版本
   - 确保SQLite3扩展已启用
   - 确保Apache/Nginx已安装并启用URL重写

2. **上传文件**
   - 将所有文件上传到服务器
   - 将网站根目录指向 `public` 目录

3. **初始化数据库**
   ```bash
   # 在项目根目录运行
   php database/init.php
   ```
   这将创建数据库表并添加默认管理员账户：
   - 用户名：Admin
   - 密码：Flos123456

4. **配置服务器**
   - **Apache**：确保 `.htaccess` 文件生效，启用 `mod_rewrite` 模块
   - **Nginx**：添加以下配置：
     ```nginx
     location / {
         if (!-e $request_filename) {
             rewrite ^(.*)$ /index.php last;
             break;
         }
     }
     ```

5. **访问系统**
   - 前台地址：http://your-domain.com/
   - 管理后台：http://your-domain.com/admin/flos

## 系统特点

1. **轻量级**：使用原生PHP开发，无框架依赖，部署简单
2. **安全**：密码加密存储，输入验证，防止SQL注入
3. **易用**：简洁的用户界面，清晰的操作流程
4. **功能完整**：包含所有要求的备案管理功能
5. **本地数据库**：使用SQLite，无需额外配置数据库服务

## 注意事项

1. **生产环境**：
   - 建议在生产环境中设置 `config/config.php` 中的 `debug` 为 `false`
   - 确保 `database` 目录权限正确，防止数据库文件被直接访问

2. **备份**：
   - 定期备份 `database/flos_icp.db` 文件
   - 备份重要配置文件

3. **维护**：
   - 定期清理过期的备案记录
   - 监控服务器运行状态

## 版权信息

Copyright Float Studio/Flos Float Community/XuanYi Cloud 2018~2026. All rights Reserved.
