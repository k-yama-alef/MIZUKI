<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// 追加
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;

// 2021/02/02追加
// use AuthenticatesAndRegistersUsers；

class AlterUserController extends Controller
{
  // protected $username = 'loginid';// 2021/02/02追加
  /*
  入力チェック項目
  */
  private $formItems = [
    'code',
    'password',
    'password_confirmation'
  ];
  private $rules = [
    'code' => ['required'],
    'password' => ['required', 'min:5', 'regex:/^[a-zA-Z0-9]+$/', 'confirmed'],
    'password_confirmation' => ['required', 'min:5', 'regex:/^[a-zA-Z0-9]+$/']
  ];
  private $messages = [
    'code.required' => 'ユーザーコードを入力してください。',
    'code.KCodeCheck' => '社員マスタで未登録なコードです。',//追加
    'password.required' => 'パスワードを入力してください。',
    'password.min' => 'パスワードは５文字以上で入力してください。',
    'password.regex' => 'パスワードには英数字のみ含めることが出来ます。',
    'password.confirmed' => 'パスワードとパスワード(確認用)が一致しません。',
    'password_confirmation.required' => 'パスワード(確認用)を入力してください。',
    'password_confirmation.min' => 'パスワード(確認用)は５文字以上で入力してください。',
    'password_confirmation.regex' => 'パスワード(確認用)には英数字のみ含めることが出来ます。'
  ];

  /*
  画面遷移時
  */
  public function index() {

    if (Auth::check() ) {
      // info('index Auth::check True');
      // ログイン済みのときの処理
      return redirect('punch');
    } else {
      // info('index Auth::check False');
      // ログインしていないときの処理
      return view('alteruser');
    }

  }

}
