<?php 

return [
	'debug' => true,
	'providers' => array(
		\framework\Session\SessionServiceProvider::class,
		\framework\Cache\CacheServiceProvider::class
	),
	'aliases' => array(
		'request' => \framework\Http\Request::class,
		'response' => \framework\Http\Response::class,
		'logger' => \framework\Logger\Logger::class,
		'view' => \framework\View\View::class
	)
];

?>