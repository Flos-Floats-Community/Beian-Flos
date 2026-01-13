<?php
session_start();

// 清除管理员会话数据
unset($_SESSION['admin_user_id']);
unset($_SESSION['admin_username']);

// 销毁会话
session_destroy();

// 重定向到管理员登录页
header('Location: login.php');
exit;
?>