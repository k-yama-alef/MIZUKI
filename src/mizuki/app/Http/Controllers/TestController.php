<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // DB ファサードを use する
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Facades\AlefCommon;
use App\Facades\MZCommon;
use Carbon\Carbon;
use App\Http\Controllers\TestController;

class TestController extends Controller
{
    /*-----------
    入力チェック処理
    -----------*/
    public function check(Request $req){
        try {
                         
              $array['Hinban'] = $req->input('hinban');
              $param_select = $array;

              $strSQL = "(";
              $strSQL .=" SELECT";
              $strSQL .=" 図面番号";
              $strSQL .=" FROM  部品マスタ";
              $strSQL .= " WHERE 図面番号 = :Hinban";
              $strSQL .=" )";
              $values = DB::select($strSQL,$param_select);
              if(count($values)>0){
                  return response("OK");
              }{
                  return response("図面番号[" . $req->input('hinban') . "]はマスターに未登録です。");
              } 
        } catch(\Exception $ex){
            return response($ex->getMessage());
        }

        return response("OK");
    }
}