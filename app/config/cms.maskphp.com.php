<?php
/*
 * +------------------------------------------------------------------------+
 * | MaskPHP - Feel free and code happy                                     |
 * | @package       : MaskPHP                                               |
 * | @authors       : MaskPHP                                               |
 * | @copyright     : Copyright (c) 2016                                    |
 * | @since         : Version 1.0.0                                         |
 * | @website       : http://www.maskphp.com                                |
 * | @email         : support@maskphp.com                                   |
 * | @require       : PHP version >= 5.4.0                                  |
 * +------------------------------------------------------------------------+
 */
// Load ENV VARIABLE
$dotenv = new \Dotenv\Dotenv(__DIR__);
$dotenv->overload();


return [
    'database'  => [
        'database'          =>[
            'default'   => [
                'driver'    => 'mysql',
                'host'      => getenv('DB_HOST'),
                'database'  => getenv('DB_DATABASE'),
                'username'  => getenv('DB_USERNAME'),
                'password'  => getenv('DB_PASSWORD'),
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => ''
            ],
        ]
    ]
];