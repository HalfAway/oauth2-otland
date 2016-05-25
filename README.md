### Example
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

}
```

### Laravel 5 example
```php
use pandaac\OAuth2OtLand\Providers\OtLand;

try {

    $otland = app(OtLand::class);

    if (! $request->has('code')) {
        $url = $otland->getAuthorizationUrl();

        $request->session()->put('oauth2state', $otland->getState());

        return redirect($url);
    }

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
    return redirect('/');
}
```