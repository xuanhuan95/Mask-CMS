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

class Image{
	public
		$width			= 0,
		$height			= 0,
		$x				= 0,
		$y				= 0,
		$dst_x 			= 0,
		$dst_y 			= 0,
		$quality 		= 75,
		$rotate 		= 0,
		$auto_scale 	= true,
		$cache 			= true;

	protected
		$source 		= null,
		$src_width		= 0,
		$src_height		= 0,
		$src_mine 		= 'image/jpeg';

	function __construct(){
		//Check if GD extension is loaded
		if(!extension_loaded('gd') && !extension_loaded('gd2')){
			throw new \Exception('Library\Image : GD is not loaded...');
		}
	}

	/**
	 * get content 
	 * @param  string|resource $file
	 */
	public function get_content($file){
		// file
		if(is_string($file) && (preg_match('/^http:\/\/.*$/i', $f = $file) || (preg_match('/\.[a-zA-Z]+$/', $file) && ($f = get_readable($file))))){
			$source = file_get_contents($f);
		}
		// upload & temp
		elseif((is_array($file) && isset($file['tmp_name'])) || preg_match('/^(\/|[a-zA-Z]+\:\\\)(.*?)/', $file)){
			if(is_array($file)){
				$file = $file['tmp_name'];
			}

			$handle = fopen($file, 'r');
			$content = fread($handle, filesize($file));
			fclose($handle);

			$source = (string)$content;
		}
		// content
		else{
			$source = $file;
		}

		// create tmp
		$this->source = tempnam('/tmp', '');
		$handle = fopen($this->source, 'w');
		fwrite($handle, $source);
		fclose($handle);

		// get resouce info
		try{
			$info = getimagesize($this->source);
		}catch(\Exception $e){
			return $this;
		}

		// not get info
		if(!is_array($info) || count($info) < 3){
			return $this;
		}

		// set resource info
		$this->src_width 	= $info[0];
		$this->src_height	= $info[1];
		$this->src_mime 	= $info['mime'];

		return $this;
	}

	/**
	 * crop image
	 */
	public function crop($width, $height, $auto_scale = true, $src_x = 0, $src_y = 0, $dst_x = 0, $dst_y = 0){
		$scale = $this->src_width/$this->src_height;

		if($width > $this->src_width){
			$width = $this->src_width;
		} 

		if($height > $this->src_height){
			$height = $this->src_height;
		}

		if(!$width && !$height){
			$width = $this->src_width;
			$height = $this->src_height;
		}elseif(!$width){
			if($auto_scale){
				$width = $height * $scale;
			}else{
				$width = $this->src_width;
			}
		}elseif(!$height){
			if($auto_scale){
				$height = $width/$scale;
			}else{
				$height = $this->src_height;
			}
		}else{
			$this->width 	= $width;
			$this->height 	= $height;
		}

		if($auto_scale){
			if($this->src_width > $this->src_height){
				$height = floor($width/$scale);
			}else{
				$width = floor($height*$scale);
			}
		}

		$this->width 		= $width;
		$this->height 		= $height;
		$this->auto_scale	= $auto_scale;
		$this->x 			= $src_x;
		$this->y 			= $src_y;
		$this->dst_x 		= $dst_x;
		$this->dst_y 		= $dst_y;

		return $this;
	}

	/**
	 * display new image
	 */
	public function display(){
		return $this->draw(null, true);
	}

	/**
	 * save image
	 * @param  string $file
	 */
	public function save($file){
		return $this->draw($file, false);
	}

	/**
	 * draw new image
	 * @param  string  $new_file
	 * @param  boolean $display
	 */
	protected function draw($new_file, $display = false){
		// get new image type
		list($mime, $type) = explode('/', $this->src_mime);

		// convert to new type
		$src_image = $this->convert_type($this->source, $type);

		// delete temp
		unlink($this->source);

		// set default
		if(!$this->width){
			$this->width = $this->src_width;
		}

		if(!$this->height){
			$this->height = $this->src_height;
		}

		if(!$this->quality){
			$this->quality = 75;
		}

		// new image
		$dst_image = imagecreatetruecolor($this->width, $this->height);
		//$this->transparency($dst_image);

		if($this->auto_scale){
			imagecopyresampled($dst_image, $src_image, $this->dst_x, $this->dst_y, $this->x, $this->y, $this->width, $this->height, $this->src_width, $this->src_height);
		}else{
			imagecopy($dst_image, $src_image, $this->dst_x, $this->dst_y, $this->x, $this->y, $this->src_width, $this->src_height);
		}

		// rotate
		$this->rotate($dst_image, $this->rotate);

		// get image type
		if($new_file && preg_match('/(.*?)\.([a-z]+)$/', $new_file, $m)){
			$type = strtolower($m[2]);
		}

		// display image
		if($display){
			$mime = 'image/' . ($type == 'jpg' ? 'jpeg' : $type);
			header('Content-Type: ' . $mime);
			header('Cache-control: max-age=' . (60*60*24*365));
			header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
			if($this->cache && isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])){
				header('HTTP/1.1 304 Not Modified');
			}
		}

		// save file
		switch($type){
			case 'gif':
				imagegif($dst_image, $new_file);
				break;
			case 'png':
				imagepng($dst_image, $new_file, $this->png_quality($this->quality), PNG_ALL_FILTERS);
				break;
			case 'jpeg':
			case 'jpg':
				imagejpeg($dst_image, $new_file, $this->quality);
				break;
		}

		// destroy
		imagedestroy($dst_image);
		imagedestroy($src_image);

		// reset all config
		$this->reset();

		return $this;
	}

	/**
	 * get png quality
	 */
	protected function png_quality($quality = 75){
		$quality = round($quality/10);
		return $quality > 9 ? 9 : $quality;
	}

	/**
	 * rotate
	 * @param  resource  &$source
	 * @param  int $rotate
	 */
	public function rotate(&$source, $rotate = 0){
		if($rotate == 0){
			return $this;
		}

		$transparent = imagecolorallocatealpha($source, 255, 255, 255, 127);
		$source = imagerotate($source, $rotate, $transparent, 0);
		imagealphablending($source, false);
		imagesavealpha($source, true);
		imagefill($source, 0, 0, $transparent);

		return $this;
	}

	/**
	 * convert image to new type
	 * @param  string|resource $source
	 * @param  string $type
	 */
	public function convert_type($source, $type = 'jpg'){
		try{
			switch($type){
				case 'gif':
					return imagecreatefromgif($source);
					break;

				case 'jpeg':
				case 'jpg':
					return imagecreatefromjpeg($source);
					break;

				case 'png': 
					return imagecreatefrompng($source);
					break;
			}
			return $source;
		}catch(\Exception $e){
			throw new \Exception('Library\Image->convert_type(...) : ' . $e);
		}
	}

	/**
	 * set transparency
	 * @param  resource $res
	 */
	public function transparency(&$src){
		imagealphablending($src, false);
		imagesavealpha($src, true);
		//$transparent = imagecolorallocatealpha($src, 255, 255, 255, 127);
		$transparent = imagecolorallocate($this->hex2rgb('#FFFFFF', $this->quality));
		imagefill($src, 0, 0, $transparent);
		return $this;
	}

	public function hex2rgb($hex, $alpha = false){
		$hex = str_replace('#', '', $hex);
		if(strlen($hex) == 6){
			$rgb['r'] = hexdec(substr($hex, 0, 2));
			$rgb['g'] = hexdec(substr($hex, 2, 2));
			$rgb['b'] = hexdec(substr($hex, 4, 2));
		}elseif(strlen($hex) == 3){
			$rgb['r'] = hexdec(str_repeat(substr($hex, 0, 1), 2));
			$rgb['g'] = hexdec(str_repeat(substr($hex, 1, 1), 2));
			$rgb['b'] = hexdec(str_repeat(substr($hex, 2, 1), 2));
		}else{
			$rgb['r'] = '0';
			$rgb['g'] = '0';
			$rgb['b'] = '0';
		}

		if(is_int($alpha)){
			$rgb['a'] = floor($alpha/100);
		}
		return $rgb;
	}

	/**
	 * reset config
	 */
	public function reset(){
		// destination
		$this->width		= 0;
		$this->height		= 0;
		$this->x			= 0;
		$this->y			= 0;
		$this->dst_x		= 0;
		$this->dst_y		= 0;
		$this->quality 		= 75;
		$this->rotate 		= 0;
		$this->auto_scale 	= true;
		$this->cache 		= true;

		// resource
		$this->source 		= null;
		$this->src_width	= 0;
		$this->src_height	= 0;
		$this->src_mime 	= 'image/jpeg';

		return $this;
	}


	/**
	 * expand method
	 * @param  string $name
	 * @param  array $args
	 */
	function __call($name, $args){
		//return \M::get('event')->expand('system.image.expand.' . $name, $args, $this);
	}
}