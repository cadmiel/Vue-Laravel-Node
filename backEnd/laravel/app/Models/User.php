<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use OAuth2ServerExamples\Entities\UserEntity;

class User extends Authenticatable implements MustVerifyEmail, UserRepositoryInterface
{
    use HasApiTokens;
    use Notifiable;
    use SoftDeletes;

    protected $guard_name = 'api';

    /** @var array */
    protected $fillable = [
        'name',
        'email',
        'password',
        'first_password',
        'blocked',
        'active',
        'super_admin',
        'change_password',
        'password_changed_at',
        'last_login',
        'acting_level',
        'username',
        'provider',
        'provider_user_id',
    ];

    /** @var array */
    protected $hidden = [
        'password',
        'first_password',
        'remember_token',
    ];

    /** @var array */
    protected $casts = [
        'email_verified_at' => 'datetime:d/m/Y H:i:s',
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
        'deleted_at' => 'datetime:d/m/Y H:i:s',
        'super_admin' => 'boolean',
        'change_password' => 'boolean',
    ];

    /** @return mixed */
    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    public static function findForPassportSocialite($provider, $id)
    {
        return User::where('provider', $provider)->where('provider_user_id', $id)->first();
    }

    /**
     * Get a user entity.
     *
     * @param string                $username
     * @param string                $password
     * @param string                $grantType The grant type used
     * @param ClientEntityInterface $clientEntity
     *
     * @return UserEntityInterface|null
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ) {

        // TODO: Implement getUserEntityByUserCredentials() method.
    }


    public function getIdentifier()
    {
        return $this->id;
    }
}
