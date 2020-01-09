<?php

namespace App\Providers;

use InvalidArgumentException;
use Laravel\Socialite\Facades\Socialite;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;

class UserSocialRepository implements UserSocialRepositoryInterface {

    /**
     * {@inheritdoc}
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ) {
        // no use implemented as UserSocialRepository extends UserRepository Class
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserFromSocialProvider($accessToken, $socialProvider, $grantType, ClientEntityInterface $clientEntity){
        try {

            $socialUser = Socialite::driver($socialProvider)->stateless()->user();

            $provider = config('auth.guards.api.provider');

            if (is_null($model = config('auth.providers.'.$provider.'.model'))) {
                throw OAuthServerException::serverError('Unable to determine authentication model from configuration.');
            }
            if (method_exists($model, 'findForPassportSocialite')) {
                $user = $model::findForPassportSocialite($socialProvider, $socialUser->getId());
                if(!$user) {
                    return;
                }

                return $user;
            }else{
                throw OAuthServerException::serverError('method "findForPassportSocialite" not implemented in authentication model from configuration.');
            }
        } catch (InvalidArgumentException $e) {
            throw OAuthServerException::invalidRequest('provider');
        } catch (\Exception $e) {
            throw OAuthServerException::serverError($e->getMessage());
        }

    }
}