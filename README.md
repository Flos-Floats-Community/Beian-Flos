# 浮云社 ICP 备案系统

基于 **ThinkPHP 6.1** + **SQLite** 的 ICP 备案查询与管理系统。

## 功能特性

- 站长注册/登录/提交备案申请
- 管理员审核（`/admin/flos`）
- 安装向导（`install.php`）
- 伪静态（Apache/Nginx 兼容）

## 环境要求

- PHP >= 7.4
- 启用 `pdo_sqlite` 扩展
- Web服务器（Apache/Nginx）

## 快速部署

1. 将项目文件上传到Web目录
2. 设置 `runtime/` 目录可写权限
3. 访问 `http://your-site/install.php` 进行初始化安装
4. 根据提示设置管理员账号
5. 安装完成后删除 `install.php` 文件
6. 访问首页开始使用

## 安全提示

- 安装后务必删除 `install.php`
- 建议修改默认管理员密码
- 生产环境请关闭调试模式

## 路由说明

- 首页：`/`
- 用户注册：`/user/register`
- 用户登录：`/user/login`
- 提交备案：`/user/apply`
- 管理员登录：`/admin/flos/login`
- 备案管理：`/admin/flos/manage`

## 技术栈

- 后端框架：ThinkPHP 6.1
- 数据库：SQLite
- 模板引擎：ThinkTemplate
- 前端：HTML/CSS/JavaScript
