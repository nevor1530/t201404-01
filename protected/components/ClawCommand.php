<?php
include_once(dirname(__FILE__).'/../extensions/simple_html_dom.php');

class ClawCommand extends CConsoleCommand
{
	protected function load($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);	// 当根据Location:重定向时，自动设置header中的Referer:信息
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);	// 在启用CURLOPT_RETURNTRANSFER的时候，返回原生的（Raw）输出
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);	// 启用时会将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量
		curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$cookie = file_get_contents(dirname(__FILE__).'/../runtime/cookie');
		curl_setopt($ch, CURLOPT_COOKIE, $cookie);
		
		$html = curl_exec($ch);
		return $html;
	}
	
	protected function check_login($html){
		$titleDom = $html->find('title', 0);
		if (strpos($titleDom->plaintext, '登录') !== false){
			return false;
		} else {
			return true;
		}
	}
}