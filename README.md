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