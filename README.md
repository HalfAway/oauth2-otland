# OAuth2 Client for OtLand.net
This package allows your site users to authenticate themselves through their OtLand.net account, which in turn returns an object with their OtLand details for you to do which what you see fit.

## Requirements
+ PHP 5.5.9+
+ OtLand client credentials
  - The only way to receive your own client ID & secret is by sending [@Mark](https://otland.net/members/mark.1/) a private message requesting it. You will have to provide him with a project name, a short description of it, as well as a redirect URI. It is then up to him whether you'll be granted the necessary credentials or not.

## Install
##### Via Composer
```
composer require pandaac/oauth2-otland
```

## OAuth2 Options
When instantiating the `pandaac\OAuth2OtLand\Providers\OtLand` object, you may pass an array of options as its first argument.
##### Client ID _(required)_
Assign your Client ID to the `clientId` key.
##### Client Secret _(required)_
Assign your Client Secret to the `clientSecret` key.
##### Redirect URI _(required)_
Assign your Redirect URI to the `redirectUri` key.
##### Scopes
Assign your scopes as an array to the `scope` key. It defaults to `['read']`.

> _The API that OtLand.net uses is [bdApi](https://github.com/xfrocks/bdApi/blob/master/docs/api.markdown). Please refer to their documentation for anything beyond simple authorization._

## Examples
##### Laravel 5.x
Add the OtLand OAuth2 client service provider to the service providers array in `./config/app.php`.
```php
pandaac\OAuth2OtLand\FrameworkIntegration\Laravel\OtLandOAuth2ServiceProvider::class
```
You should then publish the package configuration files by running the following artisan command.

```
php artisan vendor:publish --provider="pandaac\OAuth2OtLand\FrameworkIntegration\Laravel\OtLandOAuth2ServiceProvider"
```

> _You may edit your OAuth2 credentials directly in `./config/oauth2-otland.php`, although it is strongly recommended to do so through your `.env` file instead (`OTLAND_KEY`, `OTLAND_SECRET` & `OTLAND_REDIRECT`)._

and finally define a route similiar to that as shown below
```php
use Illuminate\Http\Request;
use pandaac\OAuth2OtLand\Providers\OtLand;

$router->get('/otland', function (Request $request) {
    try {

        $otland = app(OtLand::class);

        // Redirect the user to the authorization url if no code was provided
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

    // Redirect the user to the authorization url if no code was provided
    if (! isset($_GET['code'])) {
        $url = $otland->getAuthorizationUrl();

        $_SESSION['oauth2state'] = $otland->getState();

        header('Location: '.$url);
        exit;
    }

    // If the state is invalid, redirect the user
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

## Contributing
Please refer to the [PSR-2 guidelines](http://www.php-fig.org/psr/psr-2/) and squash your commits together into one before submitting a pull request.

Thank you.
