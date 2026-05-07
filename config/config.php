<?php
// 配置文件
return [
    // 应用名称
    'app_name' => '浮云社 ICP备案站点',
    // 应用调试模式
    'debug' => true,
    // 数据库配置
    'database' => [
        'dsn' => 'sqlite:' . __DIR__ . '/../database/flos_php.db',
    ],
    // 会话配置
    'session' => [
        'timeout' => 3600,
    ],
    // 默认密码
    'default_password' => 'Flos123456',
];
