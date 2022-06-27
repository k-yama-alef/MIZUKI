<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB; // 追加(登録条件変更用)

class LogInCheck implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // 追加分、ログイン情報に未登録かチェックする。
        return $this->FnLoginCheck($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'ログイン情報が登録されていないか、付与されている権限が違います.';
    }
    /**
     * ログイン情報にデータがあるかどうかチェックする.
     * @param  \Illuminate\Support\Facades\DB
     */
    public function FnLoginCheck($code){

      $strSQL  = "SELECT";
      $strSQL .= " ログインID";
      $strSQL .= " FROM ログイン情報";
      $strSQL .= " WHERE ログインID='" . $code . "'";
      $strSQL .= " AND 権限=10";
      $value = DB::select($strSQL);

      if($value != null){
        // データ有。
        return true;
      }else {
        return false;
      }
    }
    // 追加END
}
