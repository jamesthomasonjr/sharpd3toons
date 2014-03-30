<?php require 'vendor/autoload.php';

use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpKernel;
use GuzzleHttp\Client;

$app = new Silex\Application();
$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__.'/views'
));

$app->get('/', function() use($app) {
	$subRequest = Request::create('/sharp-1324/');
	return $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false);
});

$app->get('/{account}/', function($account) use($app) {

	$client = new Client();

	$response = $client->get('http://us.battle.net/api/d3/profile/'.$account.'/');
	$profile = $response->json();

	return $app['twig']->render('account.twig', array(
		'account' => $account,
		'profile' => $profile
	));
});

$app->run();
?>
