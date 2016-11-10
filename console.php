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

// set request URI
$_SERVER['REQUEST_URI'] = trim(isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : '', '/');

// autoload
require_once getcwd() . '/vendor/autoload.php';

/**
 * run application
 */
M::route()->response();

// benchmark
echo "\n\n";
echo 'Excution time: ' . excution_time(APP_TIME_START);
echo "\n";
echo 'Memory usage: ' . memory_usage(memory_get_peak_usage()) . ' / ' . memory_usage(memory_get_peak_usage(true));
echo "\n";