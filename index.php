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
$app->get('/{account}/', 'routes.controller:account');
$app->get('/{account}/{hero}/', 'routes.controller:hero');

$app->run();

?>
