<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;// 追加
use Illuminate\Support\Facades\Auth;

use App\User;//追加

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        $validator = Validator::make($data, [
          'loginid' => ['required'],
          'password' => ['required', 'min:5', 'regex:/^[a-zA-Z0-9]+$/']
        ]);
        $validator->setAttributeNames(array(
          'loginid' => 'ログインID',
          'password' => 'パスワード'
        ));
        return $validator;

    }
    /**
     * Illuminate\Foundation\Auth\AuthenticatesUsers
     *
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        //LoginControllerクラス内にusername()を追記
        //ログインに使うフィールド名を設定する
        return 'loginid';
    }

    /**
     *
     *
     * ログイン直後の処理を書き込む
     *
     * @return string
     */
    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {

    }
}
