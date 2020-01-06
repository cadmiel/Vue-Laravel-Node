<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Route;

class AuthController extends Controller
{

    use AuthenticatesUsers {
        login as protected laravelLogin;
    }

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param Request $request
     * @return JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function auth(Request $request)
    {
        $laravelLogin = $this->laravelLogin($request);

        return $laravelLogin;

    }

    public function username()
    {
        return 'username';
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user()) ?: redirect()->intended($this->redirectPath());
    }

    /**
     * @param Request $request
     * @param         $user
     * @return JsonResponse|mixed
     * @throws \Exception
     */
    protected function authenticated(Request $request, $user)
    {

        $tokenRequest = Request::create(
            '/oauth/token',
            'POST',
            $request->all()
        );

        $tokenRequest->headers = $request->headers;

        $callback = Route::dispatch($tokenRequest);

        $array_retorno = json_decode((string)$callback->getContent(), true);

        if ($callback->getStatusCode() <> 200) {
            return response()
                ->json($array_retorno)
                ->setStatusCode($callback->getStatusCode());
        }

        $array_retorno['email_verified'] = $user->hasVerifiedEmail();
        $array_retorno['change_password'] = $user->change_password;

        return $array_retorno;
    }
}
