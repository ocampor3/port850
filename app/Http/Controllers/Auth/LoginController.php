<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Auth;
use App\Models\Site;
use App\User;

use Session;

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
        $this->middleware('guest')->except('logout');
    }

    public function authenticated()
    {
        Session::put('active_user',Auth::user()->Id);
        if(Auth::user()->UserGroup != "Visitor" && Auth::user()->UserGroup != "Member")
        {
            return redirect('/v1/site');
        }
        else
        {
            $user = User::with('usersite.site')->where('Id', Auth::user()->Id)->first();

            return redirect('/v1/site/'. $user['usersite'][0]['site']->SiteCode);
        }
    }
}
