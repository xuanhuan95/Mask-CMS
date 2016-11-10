<?php
/*
 * +------------------------------------------------------------------------+
 * | MaskPHP - A PHP Framework For Beginners                                |
 * | @package       : MaskPHP                                               |
 * | @authors       : MaskPHP                                               |
 * | @copyright     : Copyright (c) 2015, MaskPHP                           |
 * | @since         : Version 1.0.0                                         |
 * | @website       : http://maskphp.com                                    |
 * | @e-mail        : support@maskphp.com                                   |
 * | @require       : PHP version >= 5.3.0                                  |
 * +------------------------------------------------------------------------+
 */

namespace Library;

class Crypt{
	public 
		$crypt_key	= null;
	protected
		$iv 		= null;

	function __construct($key = null){
		// set crypt key
		if($key){
			$this->set_crypt_key($key);
		}
	}

	/**
	 * set crypt key
	 * @param  string $key
	 */
	public function set_crypt_key($key){
		$this->crypt_key = hash('sha256', $key, TRUE);
		$this->iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_RAND);
		return $this;
	}

	/**
	 * get crypt key
	 */
	public function get_crypt_key(){
		return $this->crypt_key;
	}

	/**
	 * encrypt string
	 * @param  string $input
	 */
	public function encrypt($input){
		return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->crypt_key, $input, MCRYPT_MODE_ECB, $this->iv));
	}

	/**
	 * decrypt string
	 * @param  string input
	 */
	public function decrypt($input){
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->crypt_key, base64_decode($input), MCRYPT_MODE_ECB, $this->iv));
	}

	/**
	 * Generate a random string
	 * @param  int $length
	 */
	public function gen_random_string($length = 32){
		$salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$len = strlen($salt);
		$makepass = '';

		$stat = '';
		try{
			$stat = stat(__FILE__);
		}catch(\Exception $e){}

		if(!is_array($stat)){
			$stat = array(php_uname());
		}

		mt_srand($this->crc32(microtime() . implode('|', $stat)));

		for($i = 0; $i < $length; $i ++){
			$makepass .= $salt[mt_rand(0, $len - 1)];
		}

		return $makepass;
	}

	/**
	 * get crypt string
	 * @param  string $plaintext
	 * @param  string $salt
	 * @param  string $hash
	 */
	public function get_crypt_string($plaintext, $salt = '', $hash = 'md5'){
		return call_user_func($hash, $plaintext . ($salt ? $salt : ''));
	}

	/**
	 * encrypt & decrypt number 
	 * @param  int $n
	 */
	public function num_hash($n){
		$l1 = ($n >> 16) & 65535;
		$r1 = $n & 65535;
		for($i = 0; $i < 3; $i++) {
			$l2 = $r1;
			$r2 = $l1 ^ (int) ((((1366 * $r1 + 150889) % 714025) / 714025) * 32767);
			$l1 = $l2;
			$r1 = $r2;
		}
		return ($r1 << 16) + $l1;
	}
	
	/**
	 * fix crc32
	 * @param  string $str
	 */
	public function crc32($str){
		return sprintf('%u', crc32($str));
	}

	/**
	 * expand method
	 * @param  string $name
	 * @param  array $args
	 */
	/*function __call($name, $args){
		return \M::get('event')->expand('system.crypt.expand.' . $name, $args, $this);
	}*/
}