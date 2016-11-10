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

class Error extends Controller{
	function err_404(){
		$this->setHeader("HTTP/1.0 404 Not Found");
		echo "404 - File or directory not found";
	}
}

