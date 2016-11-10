<?php
/*
 * +------------------------------------------------------------------------+
 * | MaskPHP - The Art of simple code                                       |
 * | @package       : MaskPHP                                               |
 * | @authors       : MaskPHP                                               |
 * | @copyright     : Copyright (c) 2016                                    |
 * | @since         : Version 1.0.0                                         |
 * | @website       : http://www.maskphp.com                                |
 * | @email         : support@maskphp.com                                   |
 * | @require       : PHP version >= 5.4.0                                  |
 * +------------------------------------------------------------------------+
 */

// autoload
require_once dirname(getcwd()) . '/vendor/autoload.php';

// run application
M::route()->response();