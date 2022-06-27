<?php

namespace App\Library;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Facades\AlefCommon;
use DateTime;
class MZCommon
{
  //配列（Array）からオブジェクト（Object）に変換する
  // DB::selectの配列はオブジェクトのでそれに合わせる
  public function FntoObject($array) {
    $object = json_encode($array);
    $obj = json_decode($object);
    return $obj;
  }
  //現在の時刻を取得する。（実機で確認する必要はあるが、取りあえずそろえる意味でSQLServerから取得する）
  // $strGenDate=現在の時間
  public function FnNowDateTimeGet(){
    $data = new DateTime(date('Y-m-d H:i:s'));

    $strSQL  = "select GETDATE() as 現在日時";
    $value = DB::select($strSQL);
    if($value == null){
        //レコードが存在しない
    }else{
      $data = new DateTime($value[0]->現在日時);
    }
    // info($data->format("YmdHi"));
    return $data;
  }
}
