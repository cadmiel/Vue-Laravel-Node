<?php

namespace App\Providers;

use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;

interface UserSocialRepositoryInterface extends UserRepositoryInterface {

	public function getUserFromSocialProvider(
		$accessToken,
        $provider,
        $grantType,
        ClientEntityInterface $clientEntity
	);

}