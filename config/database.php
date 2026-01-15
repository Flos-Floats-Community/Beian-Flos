<?php
return [
    'default' => 'sqlite',
    'connections' => [
        'sqlite' => [
            'type'            => 'sqlite',
            'database'        => app()->getRootPath() . 'runtime/database.db',
            'prefix'          => '',
            'debug'           => true,
        ],
    ],
];