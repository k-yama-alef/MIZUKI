<?php
namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;

class AuthUserProvider extends EloquentUserProvider
{
  // 追加(ログインテーブルusersに別テーブル結合)
  // 権限が10（Web用）のみログインが可能にする。
  public function retrieveById($identifier) {
    $result = $this->createModel()->newQuery()
      // ->leftJoin('ログイン情報', 'users.loginid', '=', 'ログイン情報.ログインID')
      ->leftJoin('ログイン情報', function ($join) {
        $join->on('users.loginid', '=', 'ログイン情報.ログインID');
            //  ->where('ログイン情報.権限', '=', 10);
    })
    ->select(['users.*', 'ログイン情報.ログイン者名 as name_m'])

      // ->leftJoin('ログイン情報', function ($join) {
      // $join->on('users.loginid', '=', 'ログイン情報.ログインID')
      // ->where('ログイン情報.権限', '=', 10)
      // })
      // ->select(['users.*'])
      ->find($identifier);
      // info($result);
    return $result;
  }
}
