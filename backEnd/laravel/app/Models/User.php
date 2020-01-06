<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
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
}
