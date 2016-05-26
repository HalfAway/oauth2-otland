# OAuth2 Client for OtLand.net

## Requirements
+ PHP 5.5.9+
+ OtLand client credentials
  - The only way to receive your own client ID & secret is by sending [@Mark](https://otland.net/members/mark.1/) a private message requesting it. You will have to provide him with a project name, a short description of it, as well as a redirect uri. It is then up to him whether you'll be granted the necessary credentials or not.

## Install
##### Composer
```
composer require pandaac/oauth2-otland
```

##### Manually
If composer isn't an option for you, you may install this package manually. The only dependency that this package relies on is [thephpleague's OAuth2 client package](https://github.com/thephpleague/oauth2-client). However, their package comes with its own subset of dependencies that you'd have to install as well. It can get a bit cumbersome, and it is thus recommended to use the composer method instead.

### Examples
##### Laravel 5.x
Add the `pandaac\OAuth2OtLand\FrameworkIntegration\Laravel\OtLandOAuth2ServiceProvider::class` service provider to the `./config/app.php` service providers array.

Publish the package configuration files by running the following artisan command
```
php artisan vendor:publish --provider="pandaac\OAuth2OtLand\FrameworkIntegration\Laravel\OtLandOAuth2ServiceProvider"
```

and then define a route as seen below

```php
use Illuminate\Http\Request;
use pandaac\OAuth2OtLand\Providers\OtLand;

$router->get('/otland', function (Request $request) {
    try {

        $otland = app(OtLand::class);

        // Redirect the user to the authorization url
        if (! $request->has('code')) {
            $url = $otland->getAuthorizationUrl();

            $request->session()->put('oauth2state', $otland->getState());

            return redirect($url);
        }

        // If the state is invalid, redirect the user
        if (! $request->has('state') or ($request->get('state') !== $request->session()->get('oauth2state'))) {
            $request->session()->forget('oauth2state');

            return redirect('/');
        }

        $accessToken = $otland->getAccessToken('authorization_code', [
            'code' => $request->get('code')
        ]);

        $owner = $otland->getResourceOwner($accessToken);

        dd($owner->toArray());

    } catch (Exception $e) {
        // Log errors...
    }
});
```

##### No framework

```php
use pandaac\OAuth2OtLand\Providers\OtLand;

session_start();

try {
    
    $otland = new OtLand([
        'clientId'      => 'MY-CLIENT-ID',
        'clientSecret'  => 'MY-CLIENT-SECRET',
        'redirectUri'   => 'MY-REDIRECT-URI',
    ]);

    if (! isset($_GET['code'])) {
        $url = $otland->getAuthorizationUrl();

        $_SESSION['oauth2state'] = $otland->getState();

        header('Location: '.$url);
        exit;
    }

    if (! isset($_GET['state']) or ($_GET['state'] !== $_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']);

        header('Location: /');
        exit;
    }

    $accessToken = $otland->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    $owner = $otland->getResourceOwner($accessToken);

    var_dump($owner->toArray());

} catch (Exception $e) {
    // Log errors...
}
```
