<?php

namespace Newnet\Acl\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin.guest')->except('logout');
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function showLoginForm()
    {
        return view('acl::auth.login');
    }

    protected function redirectTo()
    {
        $config = config('acl.redirect_after_login', config('core.admin_prefix'));

        return is_callable($config) ? $config() : $config;
    }

    protected function loggedOut(Request $request)
    {
        $config = config('acl.redirect_after_logout', route('admin.login'));
        $redirectAfterLogout = is_callable($config) ? $config() : $config;

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect($redirectAfterLogout);
    }
}
