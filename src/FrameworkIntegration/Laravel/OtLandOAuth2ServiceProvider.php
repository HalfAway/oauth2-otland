<?php

namespace pandaac\OAuth2OtLand\FrameworkIntegration\Laravel;

use Illuminate\Support\ServiceProvider;
use pandaac\OAuth2OtLand\Providers\OtLand;
use Illuminate\Contracts\Config\Repository;

class OtLandOAuth2ServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return void
     */
    public function boot(Repository $config)
    {
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('oauth2-otland.php'),
        ]);

        $this->mergeConfigFrom(__DIR__.'/config/config.php', 'oauth2-otland');

        $this->app->bind(OtLand::class, function () use ($config) {
            return new OtLand([
                'clientId'      => $config->get('oauth2-otland.client-id'),
                'clientSecret'  => $config->get('oauth2-otland.client-secret'),
                'redirectUri'   => $config->get('oauth2-otland.redirect-uri'),
            ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // ...
    }
}
