<?php
if (strpos($_SERVER['REQUEST_URI'], '/index.php') !== false || $_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '') {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: /safe.php");
    exit;
}
?>

<?php
// 主入口文件

// 启用会话
session_start();

// 定义应用根目录
define('APP_ROOT', dirname(__DIR__));

// 加载配置
$config = require APP_ROOT . '/config/config.php';

// 简单的路由处理
$requestUri = $_SERVER['REQUEST_URI'];

// 移除查询字符串
$path = parse_url($requestUri, PHP_URL_PATH);

// 移除前导和尾随斜杠
$path = trim($path, '/');

// 分割路径
$parts = explode('/', $path);

// 处理路由
switch ($parts[0]) {
    case '':
        // 首页
        require_once APP_ROOT . '/controllers/IndexController.php';
        $controller = new IndexController();
        $controller->index();
        break;
        
    case 'login':
        // 登录相关
        require_once APP_ROOT . '/controllers/LoginController.php';
        $controller = new LoginController();
        
        if (isset($parts[1])) {
            switch ($parts[1]) {
                case 'register':
                    $controller->register();
                    break;
                case 'doLogin':
                    $controller->doLogin();
                    break;
                case 'doRegister':
                    $controller->doRegister();
                    break;
                case 'logout':
                    $controller->logout();
                    break;
                default:
                    $controller->index();
                    break;
            }
        } else {
            $controller->index();
        }
        break;
        
    case 'record':
        // 备案相关
        require_once APP_ROOT . '/controllers/RecordController.php';
        $controller = new RecordController();
        
        if (isset($parts[1])) {
            switch ($parts[1]) {
                case 'add':
                    $controller->add();
                    break;
                case 'save':
                    $controller->save();
                    break;
                case 'my':
                    $controller->my();
                    break;
                default:
                    header('Location: /');
                    exit;
            }
        } else {
            header('Location: /');
            exit;
        }
        break;
        
    case 'admin':
        // 管理相关
        require_once APP_ROOT . '/controllers/AdminController.php';
        $controller = new AdminController();
        
        if (isset($parts[1]) && $parts[1] === 'flos') {
            $controller->index();
        } elseif (isset($parts[1]) && $parts[1] === 'verify' && isset($parts[2])) {
            $controller->verify($parts[2]);
        } elseif (isset($parts[1]) && $parts[1] === 'reject' && isset($parts[2])) {
            $controller->reject($parts[2]);
        } elseif (isset($parts[1]) && $parts[1] === 'users') {
            $controller->users();
        } elseif (isset($parts[1]) && $parts[1] === 'editUser' && isset($parts[2])) {
            $controller->editUser($parts[2]);
        } elseif (isset($parts[1]) && $parts[1] === 'deleteUser' && isset($parts[2])) {
            $controller->deleteUser($parts[2]);
        } elseif (isset($parts[1]) && $parts[1] === 'checkUpdate') {
            $controller->checkUpdate();
        } elseif (isset($parts[1]) && $parts[1] === 'upgrade') {
            $controller->upgrade();
        } else {
            header('Location: /');
            exit;
        }
        break;
        
    default:
        // 404页面
        http_response_code(404);
        echo '<h1>404 Not Found</h1>';
        echo '<p>The requested page could not be found.</p>';
        break;
}
