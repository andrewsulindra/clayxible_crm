<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use App\Models\LoginActivity;
use Auth;

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
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected $auth;
    protected $lockoutTime;
    protected $maxLoginAttempts;

    public $successStatus = 200;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->middleware('guest')->except('doLogout');
        $this->auth = $auth;
        $this->lockoutTime = 1;    // lockout for 1 minute (value is in minutes)
        $this->maxLoginAttempts = 5;    // lockout after 5 attempts
    }

        /**
     * Show login page.
     *
     * @return view
     */
    public function showLogin(Request $request)
    {
        //$data['settings'] = SystemSettings::getValue('Logo');

        return view('auth.login');
    }

    /**
     * Authenticate user credentials.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function doLogin(Request $request)
    {
        $request->validate(['email' => 'required', 'password' => 'required']);
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return redirect()->back()->with('error', 'Too many login attempts. Please relogin after '.($this->lockoutTime * 60).' seconds');
        }
        $login = Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password, 'is_active' => 1], $request->remember);
        if ($login) {
            if ($request->password == 'password'){
                $redirectTo = 'setpassword/'. Auth::guard('web')->user()->id;
            }
            else{
                $redirectTo = !empty(session('url')['intended']) ? session('url')['intended'] : 'login';
            }
            
            $login = LoginActivity::create([
                'user_id' => Auth::guard('web')->user()->id,
                'login_time' => date('Y-m-d H:i:s'),
                'user_agent' => $request->server('HTTP_USER_AGENT'),
                'ip_address' => $request->ip(),
            ]);



            return redirect($redirectTo);
        } else {
            $this->incrementLoginAttempts($request);

            return redirect()->back()->with('error', 'Wrong email or password');
        }
    }

    /**
     * Log user out.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function doLogout(Request $request)
    {
        //$user = Auth::guard('web')->user();
        //$user->last_login = date('Y-m-d H:i:s');
        //$user->save();

        Auth::guard('web')->logout();

        return redirect('login')->with('success', 'You have successfully signed out.');
    }

    /**
     * Determine if the user has too many failed login attempts.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), $this->maxLoginAttempts, $this->lockoutTime
        );
    }
}
