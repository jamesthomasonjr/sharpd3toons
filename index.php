<?php require 'vendor/autoload.php';

$app = new Silex\Application();
$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__.'/views'
));

$app->get('/', function() use($app) {
	$subRequest = Symfony\Component\HttpFoundation\Request::create('/sharp-1324');
	return $app->handle($subRequest, Symfony\Component\HttpKernel\HttpKernelInterface::SUB_REQUEST, false);
});

$app->get('/{account}/', function($account) use($app) {
	return $app['twig']->render('account.twig', array(
		'account' => $account
	));
});

$app->run();
?>
