<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;

class PassportSocialiteServiceProvider extends ServiceProvider
{

    public function register()
    {
        app()->afterResolving(AuthorizationServer::class, function (AuthorizationServer $oauthServer) {
            $oauthServer->enableGrantType($this->makeSocialGrant(), Passport::tokensExpireIn());
        });
    }

    /**
     * @return \App\Providers\SocialGrant
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function makeSocialGrant()
    {
        $grant = new SocialGrant(
            $this->app->make(UserSocialRepository::class),
            $this->app->make(RefreshTokenRepository::class)
        );
        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());
        return $grant;
    }
}