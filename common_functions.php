<?php

function post($index=FALSE){
	if(!$index)
	return $_POST;
	return isset($_POST[$index])?$_POST[$index]:FALSE;
}
function get_session($index=FALSE){
	if(!$index)
	return $_SESSION;
	return isset($_SESSION[$index])?$_SESSION[$index]:FALSE;
}
function set_session($key,$val){
	$_SESSION[$key]=$val;
}
function get($index=FALSE){
	if(!$index)
	return $_GET;
	return isset($_GET[$index])?$_GET[$index]:FALSE;
}
function request($index=FALSE){
	if(!$index)
	return $_REQUEST;
	return isset($_REQUEST[$index])?$_REQUEST[$index]:FALSE;
}
function files($index=FALSE){
	if(!$index)
	return $_FILES;
	return isset($_FILES[$index])?$_FILES[$index]:FALSE;
}
function base_url($string=FALSE){
	return $string? 'http://localhost/magento-shirt/'.$string.'/':'http://localhost/magento-shirt/'; 
}
function load($file){
	$file=strpos(".",$file)?$file:$file.".php";
	include($file);
}
?>
