<?php
session_start();

// 清除所有会话数据
session_destroy();

// 重定向到首页
header('Location: index.php');
exit;
?>