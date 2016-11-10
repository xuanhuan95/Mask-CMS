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

return [
	'route'			=> [
		'default_module'		=> 'example',
		'default_request_uri'	=> 'index/index',
		'default_error'			=> 'error/err_404',
		'url_extension'			=> '.html',
		'use_query_param'		=> true,
		'allow_hook_module'		=> [],
		'rewrite_pattern' 		=> [
			'(:any)'		=> '(.*)',
			'(:number)'		=> '([0-9]+)',
			'(:alphabet)'	=> '([a-zA-Z]+)',
			'(:begin)'		=> '^',
			'(:end)' 		=> '$'
		]
	],

	'view'			=> [
		'extension'			=> '.html',
		'driver' 			=> [
			'twig'			=> function($config, &$html){
				static $twig = null;

				// get current view
				$view = str_replace($config['path'], '', $config['view']);

				// twig init
				if(!$twig){
					$loader = new \Twig_Loader_Filesystem($config['path']);
					$loader->addPath(MODULE_PATH, 'module');
					$loader->addPath(THEME_PATH, 'theme');
					$twig = new \Twig_Environment($loader, array(
						'charset'			=> 'utf-8',
						'cache' 			=> CACHE_PATH . 'view' . DS . 'twig' . DS,
						'auto_reload' 		=> true,
						'autoescape' 		=> true,
						'strict_variables'	=> false
					));
				}
				// BASE_URL
				$twig->addGlobal('BASE_URL', BASE_URL);

				// render html
				$html = $twig->render($view, $config['data']);
			}
		]
	],

	'database'	=> [
		'database' 			=>[
			'default'	=> [
				'driver'    => 'mysql',
			    'host'      => 'localhost',
			    'database'  => 'test',
			    'username'  => 'root',
			    'password'  => '',
			    'charset'   => 'utf8',
			    'collation' => 'utf8_unicode_ci',
			    'prefix'    => ''
			],

			'backup'	=> [
				'driver'    => 'mysql',
			    'host'      => 'localhost',
			    'database'  => 'backup',
			    'username'  => 'root',
			    'password'  => '',
			    'charset'   => 'utf8',
			    'collation' => 'utf8_unicode_ci',
			    'prefix'    => ''
			]
		],

		'driver'	=> [
			'laravel'	=> function($database, &$driver){
				$driver = new \Illuminate\Database\Capsule\Manager;
				foreach($database as $k => $v){
					$driver->addConnection($v, $k);
				}
				$driver->setAsGlobal();
				$driver->bootEloquent();
			}
		]
	]
];