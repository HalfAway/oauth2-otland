<?php

require_once __DIR__.'/vendor/autoload.php';

use pandaac\OAuth2OtLand\Providers\OTRealm;

session_start();

try {
    
    $otland = new OTRealm([
        'clientId'      => 'MY-CLIENT-ID',
        'clientSecret'  => 'MY-CLIENT-SECRET',
        'redirectUri'   => 'https://serverlist.otrealm.com/auth/index.php',
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
