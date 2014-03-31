<?
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client;

class RouteController
{
  protected $request;
  protected $twig;
  protected $guzzle;

  public function __construct(Request $request, Twig_Environment $twig, Client $guzzle)
  {
    $this->request = $request;
    $this->twig = $twig;
    $this->guzzle = $guzzle;
    $this->sendFile = $sendFile;
  }

  public function index()
  {
    // Render the main page
    return $this->twig->render('main.twig');
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

  public function hero($account, $hero)
  {
    // Grab hero data
    $information = $this->grabHeroInformation($account, $hero);

    // If hero exists, show hero page
    // Otherwise, show an error page
    return ($this->validateHero($information))
    ? $this->displayHero($information)
    : $this->displayInvalidHero() ;
  }

  public function validateAccount($information)
  {
    // If the result doesn't have a "battleTag" value then
    // the account does not exist.
    return (array_key_exists('battleTag', $information->json()));
  }

  public function validateHero($information)
  {
    // If the result doesn't have an "id" value then
    // the hero does not exist.
    return (array_key_exists('id', $information->json()));
  }

  public function grabAccountInformation($account)
  {
    // Grab Account profile
    $response = $this->guzzle->get('http://us.battle.net/api/d3/profile/'.$account.'/');
    return $response;
  }

  public function grabHeroInformation($account, $hero)
  {
    // Grab hero profile
    $response = $this->guzzle->get('http://us.battle.net/api/d3/profile/'.$account.'/hero/'.$hero);
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

  public function displayHero($information)
  {
    $profile = $information->json();

    // Render the hero information page
    return $this->twig->render('hero.twig', array(
      'profile' => $profile
    ));
  }

  public function displayInvalidAccount()
  {
    // Render an error page
    return $this->twig->render('error.twig', array(
      'error' => 'Invalid Account',
      'description' => 'The provided account does not exist.'
    ));
  }

  public function displayInvalidHero()
  {
    // Render an error page
    return $this->twig->render('error.twig', array(
      'error' => 'Invalid Hero',
      'description' => 'The provided hero does not exist.'
    ));
  }

  public function asset(Application $app, $asset)
  {
    return (file_exists('/assets/'.$asset))
    ? $app->sendFile('/assets/'.$asset)
    : handleError(NULL, 404) ;
  }

  public function handleError(\Exception $e, $code)
  {
    switch ($code) {
      case 404:
        $message = "The requested page could not be found!";
        break;
      default:
        $message = "Something went wrong!";
    }

    return $this->twig->render('error.twig', array(
      'error' => 'Error '.$code,
      'description' => $message
    ));
  }
}

?>
