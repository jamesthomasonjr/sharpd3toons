<?php

require 'vendor/autoload.php';
require 'controllers/routes.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__.'/views'
));

$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app['guzzle'] = $app->share(function() use ($app) {
	return new GuzzleHttp\Client();
});

$app['routes.controller'] = $app->share(function() use ($app) {
	return new RouteController($app['request'], $app['twig'], $app['guzzle']);
});

$app->get('/', 'routes.controller:index');
$app->get('/{account}/', 'routes.controller:account')
	->assert('account', '\w{3,12}-\d{4}');
$app->get('/{account}/{hero}/', 'routes.controller:hero')
	->assert('account', '\w{3,12}-\d{4}')
	->assert('hero', '\d{1,9}');
$app->get('/assets/{asset}', 'routes.controller:asset')
	->assert('asset', '.(css|js)\z');

$app->error('routes.controller:handleError');

$app->run();

?>
