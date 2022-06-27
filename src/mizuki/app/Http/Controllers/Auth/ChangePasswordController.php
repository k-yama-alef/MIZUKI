<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use App\Http\Requests\ChangePasswordRequest;

class ChangePasswordController extends Controller
{
    //ログインしていない場合はログイン画面を表示させる
    public function __construct()
    {
       $this->middleware('auth');
    }
    //viewからパスワード変更処理を表示
    public function showChangePasswordForm()
    {
        return view('auth/passwords/change');
    }

    // public function changePassword()
    public function changePassword(ChangePasswordRequest $request)
    {
        //ValidationはChangePasswordRequestで処理
         /* ===ここにパスワード変更の処理=== */
         //パスワード変更処理
         $user = Auth::user();
         $user->password = bcrypt($request->get('password'));
         $user->save();
        // パスワード変更処理後、homeにリダイレクト
        return redirect()->route('home')->with('status', __('Your password has been changed.'));
    }
}
