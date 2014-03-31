<?php require 'vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

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

class RouteController
{
	protected $request;
	protected $twig;

	public function __construct(Request $request,Twig_Environment $twig)
	{
		$this->request = $request;
		$this->twig = $twig;
	}

	public function index()
	{
		// Show my accout by default
		return $this->account('sharp-1324');
	}

	public function account($account)
	{
		// Grab account data
		$information = $this->grabAccountInformation($account);

		// If account exists, show account page
		// Otherwise, show an error page
		return ($this->validateAccount($information))
		? $this->displayAccount($information)
		: $this->displayInvalidAccount() ;
	}

	public function validateAccount($information)
	{
		// If the result doesn't have a "battleTag" value then
		// the account does not exist.
		return (array_key_exists('battleTag', $information->json()));
	}

	public function grabAccountInformation($account)
	{
		$client = new GuzzleHttp\Client();

		// Grab Account profile
		$response = $client->get('http://us.battle.net/api/d3/profile/'.$account.'/');
		return $response;
	}

	public function displayAccount($information)
	{
		$profile = $information->json();

		// Render the account information page
		return $this->twig->render('account.twig', array(
			'profile' => $profile
		));
	}

	public function displayInvalidAccount()
	{
		// Render an error page
		return $this->twig->render('error.twig', array(
			'error' => 'Invalid Account',
			'description' => "The provided account does not exist."
		));
	}
}

$app->run();

?>
