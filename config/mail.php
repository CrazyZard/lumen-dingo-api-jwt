<?php
return [
    'driver' => env('MAIL_DRIVER', 'smtp'),
    'host' => env('MAIL_HOST', 'smtp.qq.com'),
    'port' => env('MAIL_PORT', 465),
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', '646961312@qq.com'),
        'name' => env('MAIL_FROM_NAME', 'test'),
    ],
    'encryption' => env('MAIL_ENCRYPTION', 'ssl'),//当端口为465时，要改成ssl
    'username' => env('MAIL_USERNAME'),
    'password' => env('MAIL_PASSWORD'),
    'sendmail' => '/usr/sbin/sendmail -bs',
    'markdown' => [
        'theme' => 'default',
        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

];
