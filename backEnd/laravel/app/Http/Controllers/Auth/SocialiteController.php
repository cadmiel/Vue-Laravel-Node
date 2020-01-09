<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Route;

class SocialiteController extends Controller
{


    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider(Request $request)
    {
        return Socialite::driver($request->driver)->stateless()->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $request)
    {
        $user = Socialite::driver($request->driver)->stateless()->user();

        if (!$exist = User::where('provider', $request->driver)
            ->where('provider_user_id', $user->getId())
            ->first()) {

            User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => $user->getName() + $user->getEmail(),
                'first_password' => $user->getName() + $user->getEmail(),
                'change_password' => false,
                'acting_level' => 1,
                'username' => $user->getEmail(),
                'provider_user_id' => $user->getId(),
                'provider' => $request->driver,
            ]);
        }

        $params = [
            'grant_type' => 'social',
            'client_id' => config('auth.passport_client_id'),
            'client_secret' => config('auth.passport_client_secret'),
            'accessToken' => $request->code,
            'provider' => $request->driver,
        ];
        $request->request->add($params);

        $requestToken = Request::create("oauth/token", "POST");
        $response = Route::dispatch($requestToken);

        return json_decode((string)$response->content(), true);
    }

}