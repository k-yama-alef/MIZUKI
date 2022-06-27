<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

// use Illuminate\Auth\Events\Registered;// 追加(登録条件変更用)
// use Illuminate\Http\Request;// 追加(登録条件変更用)
use App\Rules\LogInCheck;// 追加(登録条件変更用)

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
      // info('validator1');//デバック
      $validator = Validator::make($data, [
        'loginid' => ['required', 'string', 'min:1', 'max:10','alpha_num', 'unique:users', new LogInCheck],
        // 'name' => ['required'],
        'password' => ['required', 'min:5', 'regex:/^[a-zA-Z0-9]+$/', 'confirmed'],
        'password_confirmation' => ['required', 'min:5', 'regex:/^[a-zA-Z0-9]+$/']
      ]);
      // info('validator2');//デバック
      $validator->setAttributeNames(array(
        'loginid' => 'ユーザーコード',
        // 'name' => 'ユーザー名',
        'password' => 'パスワード',
        'password_confirmation' => 'パスワード(確認用)'
      ));
      info('validator3');//デバック
      return $validator;

        // return Validator::make($data, [
        //     'name' => ['required', 'string', 'max:255'],
        //     'loginid' => ['required', 'string', 'min:8', 'max:8','alpha_num', 'unique:users'],
        //     'password' => ['required', 'string', 'min:6', 'max:8', 'confirmed'],
        // ]);
    }
    // // 追加(登録条件変更用)
    // /**
    //  * [vendor\laravel\ui\auth-backend\RegistersUsers.php]からコピー
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    //  */
    // public function register(Request $request)
    // {
    //     $this->validator($request->all())->validate();
    //     // // 追加分、個人マスタに未登録かチェックする。
    //     // if ($this->FnLoginCheck($request->loginid)) {
    //     //   return back()//元の画面を再表示
    //     //       ->withInput()//前回の値を入れる（パスワードを除く）
    //     //       ->with('loginid_error1', '社員マスタに未登録のログインIDです.');//
    //     // }
    //
    //     event(new Registered($user = $this->create($request->all())));//登録
    //
    //     $this->guard()->login($user);// <- ここでログインしている
    //
    //     if ($response = $this->registered($request, $user)) {
    //         return $response;
    //     }
    //     return $request->wantsJson()
    //                 ? new JsonResponse([], 201)
    //                 : redirect($this->redirectPath());
    // }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
      // info('create');//デバック
        return User::create([
            // 'name' => $data['name'],
            'loginid' => $data['loginid'],
            'password' => Hash::make($data['password']),
        ]);
    }

}
