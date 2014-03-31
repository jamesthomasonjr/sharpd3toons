<?php

require 'vendor/autoload.php';
require 'controllers/routes.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__.'/views'
));

$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app['routes.controller'] = $app->share(function() use ($app) {
	return new RouteController($app['request'], $app['twig']);
});

$app->get('/', 'routes.controller:index');
$app->get('/{account}/', 'routes.controller:account');

$app->run();

?>
