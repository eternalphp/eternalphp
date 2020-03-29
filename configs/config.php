<?php

//公共配置文件

$config['system_name'] = '港航-红帆后台管理系统';
$config['version'] = 'v2.0';
$config['debug_mode'] = 0; //调试模式
$config['system_log'] = 1; //日志模式
$config['access_log'] = 1; //访问记录
$config['DEBUG_PATH'] = '/storage/debug/';
$config['LOG_PATH'] = '/storage/';
$config['RouterMode'] = 'RouterUrl';
$config['language'] = 1;
$config['langPacks'] = 'DB';  // or FILE
$config['token_key'] = 'php_token';
$config['uploadImageExts'] = 'png|jpg|jpeg|gif';
$config['uploadImageSize'] = 1024*10;

return $config;

?>