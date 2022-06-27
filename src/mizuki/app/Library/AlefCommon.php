<?php

namespace App\Library;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AlefCommon
{

    //システムデータの内容を取得
    public function GetSystemData($section, $key){

        $data = "";

        $param = ['section' => $section, 'key' => $key];
        $strSQL  = "SELECT DATA FROM システムデータ";
        $strSQL .= " WHERE SECTION = :section";
        $strSQL .= "   AND [KEY] = :key";   //KEYは[]で囲まないとエラーになる
        $value = DB::select($strSQL, $param);
        if(count($value)>0){
            //レコードが存在する
          $data = $value[0]->DATA;
        }

        return $data;
    }

      //システムデータの内容を取得
      public function PutSystemData($section, $key,$DATA){
  
        $param = ['section' => $section, 'key' => $key];
        $param2 = ['section' => $section, 'key' => $key, 'DATA' => $DATA];
        
        $strSQL  = "SELECT DATA FROM システムデータ";
        $strSQL .= " WHERE SECTION = :section";
        $strSQL .= "   AND [KEY] = :key";   //KEYは[]で囲まないとエラーになる
        $value = DB::select($strSQL, $param);
        if(count($value)>0){
            $strSQL = "UPDATE システムデータ SET";
            $strSQL .= " DATA = :DATA";
            $strSQL .= " WHERE [SECTION] = :section";
            $strSQL .= " AND [KEY] = :key";
            DB::update($strSQL, $param2); 
        }else{
            $strSQL = "INSERT INTO システムデータ(";
            $strSQL .= " [SECTION]";
            $strSQL .= ",[KEY]";
            $strSQL .= ",DATA";
            $strSQL .= ") VALUES (";
            $strSQL .= " :section";
            $strSQL .= ",:key";
            $strSQL .= ",:DATA";
            $strSQL .= ")";
            DB::insert($strSQL, $param2);             
        }
      }

    // 数値日付(8桁)を／付きの日付にする。
    public function L_Date($strTax)
    {
      if(strlen($strTax) == 8 && is_numeric($strTax)){
        return substr($strTax,0,4).'/'.substr($strTax,4,2).'/'.substr($strTax,6,2);
      }else{
        return $strTax;
      }
    }

    // 数値日時を／:付きの日時(時間、分)にする。
    public function L_DateTime($strTax)
    {
      if(strlen($strTax) == 12 && is_numeric($strTax)){
        return substr($strTax,0,4).'/'.substr($strTax,4,2).'/'.substr($strTax,6,2).' '.substr($strTax,8,2).':'.substr($strTax,10,2);
      }else{
        return $strTax;
      }
    }

    // 曜日番号から曜日を返す。
    public function Youbi($intW)
    {
      // 日:0  月:1  火:2  水:3  木:4  金:5  土:6
      switch ($intW) {
          case 0:
              return "日";
          case 1:
              return "月";
          case 2:
              return "火";
          case 3:
              return "水";
          case 4:
              return "木";
          case 5:
              return "金";
          case 6:
              return "土";
      }
    }

    public function null_i($so){
      If(isset($so)==false){
        return 0;
      }Else{
        return intval(trim($so));
      }
    }

    public function null_n($so){
      If(isset($so)==false){
        return 0;
      }Else{
        return (double)(trim($so));
      }
    }

    // 税抜商品の税込定価・金額の取得
    public function OutTax_Get($intTax, $crrPrice)
    {
      //切捨
      return $crrPrice + floor($crrPrice * $intTax / 100);
    }

    //税込定価・金額より割引率適用後の税込定価・金額の取得
    public function RatePrice_Get_S($crrPrice, $lngRate)
    {
      //切捨
      return floor($crrPrice * $lngRate / 100);
    }

    //税込商品の税抜定価・金額の取得
    public Function InTax_Get($intTax, $crrPrice) {
      //切上
      return ceil($crrPrice * (100 / (100 + $intTax)));
    }

    //エラーログの出力
    public function Err_log($errtext, $loginuser = ""){

      //[storage\app]に作成される

      //日付時刻を取得
      $today = date("Y-m-d H:i:s");

      //リクエストされたURLを取得
      $strrequesturl = (isset( $_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

      //顧客コード未指定の場合は、セッション情報から顧客コードを取得
      if ($loginuser === "") {
          //顧客コード(ログイン者)
          $user = Auth::user();
          if (isset($user->kcode)) {
              $loginuser = strval($user->kcode);
          }
      }

      //エラー内容
      //Storage::append('alferr.log', "[" . $today . "] USER:" . $loginuser . " " . $strrequesturl);
      //Storage::append('alferr.log', $errtext);
      Log::channel('alferr')->error('USER:' . $loginuser . ' REQUEST:' . $strrequesturl . "\n" . $errtext);

    }
}
