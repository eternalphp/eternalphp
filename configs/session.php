<?php

//session配置
$config['driver']       = 'file';
$config['cookie']		= true;
$config['session']		= true;
$config['sess_cookie_name']		= 'cn_session_utf8';
$config['sess_expiration']		= 7200;
$config['cookie_lifetime']      = null;
$config['sess_path']		    = 'sessions';
$config['sess_domain']		    = '';
$config['cookie_httponly']      = 1;
$config['gc_probability']       = 1;
$config['gc_divisor']           = 100;

return $config;
?>