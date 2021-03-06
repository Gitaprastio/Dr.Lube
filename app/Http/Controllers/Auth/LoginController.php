<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/purchase';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticated(\Illuminate\Http\Request $request, $user){

      if(auth()->user()->hasRole('sales')){
          return redirect('/sales');
      }else if(auth()->user()->hasRole('operation')){
          return redirect('/operation');
      }else if(auth()->user()->hasRole('complain')){
          return redirect('/complain');
      }else{
          return redirect('/purchase');
      }

    }

}
