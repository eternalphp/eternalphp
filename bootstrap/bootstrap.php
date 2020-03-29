<?php

/***************************************************
  * @模块：      框架初始化模块
  * @说明：      
  * @作者:       564165682@qq.com
  * @日期:       2015-10-28
***************************************************/

use framework\Container\Container;
use framework\Foundation\Application;

defined("ROOT") || define("ROOT",dirname(__DIR__));

require ROOT . '/vendor/autoload.php';

session_start();
$app = Container::getInstance();
$app->bind("Application",new Application(ROOT));
$app->get("Application")->start();

?>