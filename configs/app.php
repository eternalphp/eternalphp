<?php 

return [
	'debug' => true,
	'log' => true,
	'providers' => array(
		\framework\Session\SessionServiceProvider::class,
		\framework\Cache\CacheServiceProvider::class
	),
	'aliases' => array(
		'cookie' => \framework\Cookie\Cookie::class,
		'session' => \framework\Session\Session::class,
		'request' => \framework\Http\Request::class,
		'response' => \framework\Http\Response::class,
		'cache' => \framework\Cache\Cache::class,
		'logger' => \framework\Logger\Logger::class,
		'language' => \framework\Language\Language::class,
		'view' => \framework\View\View::class,
		'validate' => \framework\Validate\Validate::class,
		'event' => \framework\Event\Event::class,
		'notice' => \App\Event\NoticeEvent::class,
		'weixin' => \App\Event\WeixinEvent::class
	)
];

?>