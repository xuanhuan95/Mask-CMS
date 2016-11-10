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
 * | @require       : PHP version >= 5.3.0                                  |
 * +------------------------------------------------------------------------+
 */

namespace App\Example\Controller;

class index extends Controller{
	function index(){
		echo 'Hello, World!';
		echo '<br>';
		echo 'Excution time: ' . excution_time(APP_TIME_START);
		echo '<br>';
		echo 'Memory usage: ' . memory_usage(memory_get_peak_usage()) . '/' . memory_usage(memory_get_peak_usage(true));
	}
}

