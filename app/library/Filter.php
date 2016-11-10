<?php
/*
 * +------------------------------------------------------------------------+
 * | MaskPHP - A PHP Framework For Beginners                                |
 * | Authors        : Mask PHP                                              |
 * | E-mail         : support@maskphp.com                                   |
 * | Website        : http://maskphp.com                                    |
 * | PHP version    : >= 5.3.0                                              |
 * +------------------------------------------------------------------------+
 * | Copyrights(C) 2015 by MASK PHP                                         |
 * | All rights reserved                                                    |
 * +------------------------------------------------------------------------+
 */

namespace Library;

class Filter{
	public
		$charset_table = array(
			'a'	=> 'a|à|á|ả|ã|ạ|A|À|Á|Ả|Ã|Ạ|â|ầ|ấ|ẩ|ẫ|ậ|Â|Ầ|Ấ|Ẩ|Ẫ|Ậ|ă|ằ|ắ|ẳ|ẵ|ặ|Ă|Ằ|Ắ|Ẳ|Ẵ|Ặ',
			'b' => 'b|B',
			'c' => 'c|C',
			'd' => 'd|D|đ|Đ',
			'e' => 'e|è|é|ẻ|ẽ|ẹ|E|È|É|Ẻ|Ẽ|Ẹ|ê|ề|ế|ể|ễ|ệ|Ê|Ề|Ế|Ể|Ễ|Ệ',
			'f' => 'f|F',
			'g' => 'g|G',
			'h' => 'h|H',
			'i' => 'i|ì|í|ỉ|ĩ|ị|I|Ì|Í|Ỉ|Ĩ|Ị',
			'j' => 'j|J',
			'k' => 'k|K',
			'l' => 'l|L',
			'm' => 'm|M',
			'n' => 'n|N',
			'o' => 'o|ò|ó|ỏ|õ|ọ|O|Ò|Ó|Ỏ|Õ|Ọ|ô|ồ|ố|ổ|ỗ|ộ|Ô|Ồ|Ố|Ổ|Ỗ|Ộ|ơ|ờ|ớ|ở|ỡ|ợ|Ơ|Ờ|Ớ|Ở|Ỡ|Ợ',
			'p' => 'p|P',
			'q' => 'q|Q',
			'r' => 'r|R',
			's' => 's|S',
			't' => 't|T',
			'v' => 'v|V',
			'u' => 'u|ù|ú|ủ|ũ|ụ|U|Ù|Ú|Ủ|Ũ|Ụ|ư|ừ|ứ|ử|ữ|ự|Ư|Ừ|Ứ|Ử|Ữ|Ự',
			'y' => 'y|ỳ|ý|ỷ|ỹ|ỵ|Y|Ỳ|Ý|Ỷ|Ỹ|Ỵ',
			'z' => 'z|Z',
			'x' => 'x|X',
			'w' => 'w|W',
			'0'	=>	'0',
			'1' =>	'1',
			'2'	=>	'2',
			'3'	=>	'3',
			'4'	=>	'4',
			'5' =>	'5',
			'6' =>	'6',
			'7'	=>	'7',
			'8'	=>	'8',
			'9'	=>	'9'
		);

	public function unicode_to_alphabe($words){
		
		$words = preg_replace(array_map(function($str){return '/' . $str . '/u';}, $this->charset_table), array_keys($this->charset_table), $words);
		return $words;
	}

	/**
	 * is image
	 */
	public function is_image($file){
		// file
		if(preg_match('/.*\.[a-z]+$/i', $file)){
			$ext = pathinfo($file, PATHINFO_EXTENSION);
			return preg_match('/(jpg|jpeg|png|gif)/i', $ext) ? true : false;
		}

		// mime
		if(preg_match('/image\/[a-z]+/i', $file)){
			return true;
		}

		return false;
	}
	public function unicode($str){

		$ret = '';
		$charset = array(' ');

		foreach($this->charset_table as $v){
			$charset = array_merge($charset, explode('|', $v));
		}

		mb_internal_encoding("UTF-8");
		for($i=0; $i<strlen($str); $i++){
			$c = mb_substr($str, $i, 1);
			if(in_array($c, $charset)){
				$ret .= $c;
			}
		}

		return $ret;
	}
}