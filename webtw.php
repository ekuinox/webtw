<?php

/**
* @6lmx
*
* tweet : https://twitter.com/i/tweet/create status => tweet text
* setting : https://twitter.com/settings/accounts/update
* change password : https://twitter.com/settings/password/update
*/


class webtw{

	private $authenticity_token;
	private $cookie;

	function __construct($screen_name,$password){
		$this::GetAuthenticityToken();
		$this::Login($screen_name,$password);
	}

	private function GetAuthenticityToken(){
		$this->cookie = tempnam(sys_get_temp_dir(), "cookie");
		$ch = curl_init('https://twitter.com/');
		curl_setopt_array($ch, [
			CURLOPT_RETURNTRANSFER  => true,
			CURLOPT_SSL_VERIFYPEER  => false,
			CURLOPT_COOKIEJAR       => $this->cookie,
			CURLOPT_COOKIEFILE      => $this->cookie
			]);
		while (!preg_match('<input type="hidden" value="([^"]++)" name="authenticity_token">', curl_exec($ch), $matches));
		$this->authenticity_token = $matches[1];
	}

	private function Login($screen_name,$password){
		$ch = curl_init('https://twitter.com/sessions');
		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_COOKIEFILE => $this->cookie,
			CURLOPT_COOKIEJAR => $this->cookie,
			CURLOPT_POST    => true,
			CURLOPT_POSTFIELDS  => http_build_query(array(
				'session[username_or_email]'    => $screen_name,
				'session[password]'     => $password,
				'authenticity_token'        => $this->authenticity_token
				))
			));
		curl_exec($ch);
		$res = curl_getinfo($ch);
		if($res["redirect_url"] != 'https://twitter.com/'){
			throw new Exception('Login Failed', 0);
		}
	}

	function post($url,$params, $useragent = null){
		$params['authenticity_token'] = $this->authenticity_token;
		$ch = curl_init($url);
		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_COOKIEFILE => $this->cookie,
			CURLOPT_COOKIEJAR => $this->cookie,
			CURLOPT_REFERER => "https://twitter.com/",
			CURLOPT_USERAGENT => $useragent,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => http_build_query($params)
			));
		return json_decode(curl_exec($ch));
	}

	function curlPost($url, $params, $useragent = null){
		$params['authenticity_token'] = $this->authenticity_token;
		$ch = curl_init($url);
		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_COOKIEFILE => $this->cookie,
			CURLOPT_COOKIEJAR => $this->cookie,
			CURLOPT_REFERER => "https://twitter.com/",
			CURLOPT_USERAGENT => $useragent,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => http_build_query($params)
			));
		return $ch;
	}
}