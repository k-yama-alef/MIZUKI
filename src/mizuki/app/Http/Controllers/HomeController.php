<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use App\Library\AlefCommon;
// use App\Http\Controllers\AlefCommon;
use App\Facades\AlefCommon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // protected $AlefCommon;
    // public function __construct(AlefCommon $AlefCommon)
    public function __construct()
    {
        $this->middleware('auth');//認証済みユーザーだけがアクセスできるよう保護
        // $this->AlefCommon = $AlefCommon;
        // info('__construct');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::check()) {
          // info('Auth::check True');
          // メニューを作成する。
          // ***品質管理
          $array['Name'] = '新 規 登 録';
          $array['route'] = 'project/mzwp0010';
          $array['kubun'] = '01';
          $array['title'] = '品 質 管 理';// タイトルを差し込む場合に入れる
          $array['end'] = 0;
          $menu_data[] = $array;
          $array['Name'] = '検 索 画 面';
          $array['route'] = 'project/mzwp0011';
          $array['kubun'] = '01';
          $array['title'] = '';// タイトルを差し込む場合に入れる
          $array['end'] = 1;
          $menu_data[] = $array;

          // ***教育訓練
          $array['Name'] = '新 規 登 録';
          $array['route'] = 'project/mzwp0020';
          $array['kubun'] = '02';
          $array['title'] = '教 育 訓 練';// タイトルを差し込む場合に入れる
          $array['end'] = 0;
          $menu_data[] = $array;
          $array['Name'] = '検 索 画 面';
          $array['route'] = 'project/mzwp0021';
          $array['kubun'] = '02';
          $array['title'] = '';// タイトルを差し込む場合に入れる
          $array['end'] = 1;
          $menu_data[] = $array;

          // ***文書管理
          $array['Name'] = '新 規 登 録';
          $array['route'] = 'project/mzwp0030';
          $array['kubun'] = '03';
          $array['title'] = '文 書 管 理';// タイトルを差し込む場合に入れる
          $array['end'] = 0;
          $menu_data[] = $array;
          $array['Name'] = '検 索 画 面';
          $array['route'] = 'project/mzwp0031';
          $array['kubun'] = '03';
          $array['title'] = '';// タイトルを差し込む場合に入れる
          $array['end'] = 1;
          $menu_data[] = $array;

          // ***計測器管理
          $array['Name'] = '新 規 登 録';
          $array['route'] = 'project/mzwp0040';
          $array['kubun'] = '04';
          $array['title'] = '計 測 器 ・ 治 工 具 管 理';// タイトルを差し込む場合に入れる
          $array['end'] = 0;
          $menu_data[] = $array;
          $array['Name'] = '検 索 画 面';
          $array['route'] = 'project/mzwp0041';
          $array['kubun'] = '04';
          $array['title'] = '';// タイトルを差し込む場合に入れる
          $array['end'] = 1;
          $menu_data[] = $array;

          return view('home') -> with(["menu_data" => $menu_data,
                                     ]);
        } else {
          // ログインしていないときの処理
          info('Auth::check false');
          return redirect('login');
        }
          //return view('home');
    }
}
