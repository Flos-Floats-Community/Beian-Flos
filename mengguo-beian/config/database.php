<?php
// +----------------------------------------------------------------------
// | 数据库设置
// +----------------------------------------------------------------------

return [
    // 默认使用的数据库连接配置
    'default'         => 'sqlite',

    // 数据库连接配置信息
    'connections'     => [
        'sqlite' => [
            // 数据库类型
            'type'            => 'sqlite',
            // 连接dsn
            'dsn'             => 'sqlite:' . dirname(__DIR__) . '/database/beian.db',
            // 数据库编码默认采用utf8
            'charset'         => 'utf8',
            // 数据库表前缀
            'prefix'          => 'meng_',
            // 断线重连
            'break_reconnect' => false,
            // 监听SQL
            'trigger_sql'     => true,
            'deploy'          => 0,
            'rw_separate'     => false,
            'master_num'      => 1,
            'slave_no'        => '',
            'debug'           => true,
        ],
    ],
];