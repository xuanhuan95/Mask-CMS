<?php
M::import(LIBRARY_PATH. 'Filter.php');

function toArray($obj)
{
	return json_decode(json_encode($obj),1);
}

function debug($data)
{
	echo '<pre>';
	var_dump($data);
	echo '</pre>';
	die;
}

function checkIsset($str = '',$default = '')
{
	return isset($str) ? $str: $default;
}

function get_seo_link($str){
	$filter = new \Library\Filter();
	$str = convert2utf8($str);
	$str = $filter->unicode_to_alphabe($str);
	$str = preg_replace('/[^a-zA-Z-0-9]+/', '-', $str);
	$str = trim(preg_replace('/[\-]+/', '-', $str), '-');
	return $str;
}

function convert2utf8($str){
	$str = html_entity_decode(strip_tags($str), ENT_QUOTES,'utf-8');

	return $str;
}