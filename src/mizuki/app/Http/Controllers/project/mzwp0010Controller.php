<?php

namespace App\Http\Controllers\project;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // DB ファサードを use する
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Facades\AlefCommon;
use App\Facades\MZCommon;
use Carbon\Carbon;
class mzwp0010Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');//認証済みユーザーだけがアクセスできるよう保護
    }

    public function index(){
      //新規登録から表示
      $user = Auth::user();
      return $this->FnPageSet(0,"");
    }

    public function index_Kensaku(){
      //検索から表示
      $user = Auth::user();
      $itemvalues = MZCommon::FntoObject($this->FnZyouken_array_New());
      // 初期値として入れておく
      $itemvalues->年月日S="2004-01-01";
      return $this->FnPageSet_Kensaku($itemvalues,0);
    }

    public function FnRead(Request $request){
      if(trim($request->input('txtTitle'))==""){
        // 新規の場合はindexを走らせる
        return $this->index();
      }
      //条件画面よりデータを表示
      $user = Auth::user();
      
      $p_z = $this->FnZyouken_array($request);

      $param_zyouken = MZCommon::FntoObject($p_z);

       $intCheck = 0;
      if($param_zyouken->年月日S!=""){
        $intCheck = 1;

        $strtxYMD_S = $param_zyouken->年月日S;
        if($strtxYMD_S!= ""){
          $strtxYMD_S = date("Ymd", strtotime($strtxYMD_S));
        }
        $array['YMD_S'] = $strtxYMD_S;
      }
      if($param_zyouken->年月日E!=""){
        $intCheck = 1;
        $strtxYMD_E = $param_zyouken->年月日E;
        if($strtxYMD_E!= ""){
          $strtxYMD_E = date("Ymd", strtotime($strtxYMD_E));
        }
        $array['YMD_E'] = $param_zyouken->年月日E;
      }
      if($param_zyouken->顧客コード!=""){
        $intCheck = 1;
        $array['Kokyaku'] = $param_zyouken->顧客コード;
      }
      if($param_zyouken->重大レベルコード!=""){
        $intCheck = 1;
        $array['Zyuudai'] = $param_zyouken->重大レベルコード;
      }
      if($param_zyouken->発注_外注コード!=""){
        $intCheck = 1;
        $array['Gaichyuu'] = $param_zyouken->発注_外注コード;
      }
      if($param_zyouken->不適合製品タグNo!=""){
        $intCheck = 1;
        $array['FutekiNo'] = $param_zyouken->不適合製品タグNo;
      }
      if($param_zyouken->発生_社内コード!=""){
        $intCheck = 1;
        $array['Syain'] = $param_zyouken->発生_社内コード;
      }
      if($param_zyouken->図面番号!=""){
        $intCheck = 1;
        $array['Hinban'] = $param_zyouken->図面番号."%";
      }
      if($param_zyouken->セリアルNo!=""){
        $intCheck = 1;
        $array['SNO'] = $param_zyouken->セリアルNo."%";
      }
      if($param_zyouken->不適合区分コード!=""){
        $intCheck = 1;
        $array['FutekigouKubun'] = $param_zyouken->不適合区分コード;
      }
      if($param_zyouken->人的要因コード!=""){
        $intCheck = 1;
        $array['Zinteki'] = $param_zyouken->人的要因コード;
      }
      if($param_zyouken->物的要因コード!=""){
        $intCheck = 1;
        $array['Butteki'] = $param_zyouken->物的要因コード;
      }
      if($param_zyouken->発見コード!=""){
        $intCheck = 1;
        $array['Hakken'] = $param_zyouken->発見コード;
      }
      if($param_zyouken->処置コード!=""){
        $intCheck = 1;
        $array['Syochi'] = $param_zyouken->処置コード;
      }
      if($param_zyouken->是正処置!=""){
        $intCheck = 1;
        $array['Zeseisyoti'] = $param_zyouken->是正処置."%";
      }
      if($param_zyouken->是正処置実施確認日!=""){
        $intCheck = 1;
        $strtxZeseiYMD = $param_zyouken->是正処置実施確認日;
        if($strtxZeseiYMD!= ""){
          $strtxZeseiYMD = date("Ymd", strtotime($strtxZeseiYMD));
        }
        $array['ZeseiYMD'] = $strtxZeseiYMD;
      }
      if($param_zyouken->特採申請No!=""){
        $intCheck = 1;
        $array['TokusaiNo'] = $param_zyouken->特採申請No."%";
      }
      if($intCheck!=0){
        $param_select = $array;
      }else{
        $param_select = "";
      }
      
      $strSQL = "(";
      $strSQL .=" SELECT";
      $strSQL .=" ROW_NUMBER() OVER(ORDER BY 年月日,不適合製品タグNo,id) as 行番号";
      $strSQL .=",0 as DB区分";
      $strSQL .=",id";
      $strSQL .=",不適合製品タグNo";
      $strSQL .=",(case when IsNull(年月日,'')<>'' and Len(rTrim(年月日))=8 then CONVERT(datetime, 年月日, 112) else Null end) as 年月日";
      $strSQL .=",顧客コード";
      $strSQL .=",検査数";
      $strSQL .=",不適合内容";
      $strSQL .=",重大レベルコード";
      $strSQL .=",物的要因コード";
      $strSQL .=",発見コード";
      $strSQL .=",不適合区分コード";
      $strSQL .=",不適合数";
      $strSQL .=",原因";
      $strSQL .=",処置コード";
      $strSQL .=",人的要因コード";
      $strSQL .=",発注_外注コード";
      $strSQL .=",品名";
      $strSQL .=",図面番号";
      $strSQL .=",セリアルNo";
      $strSQL .=",是正処置";
      $strSQL .=",各No";
      $strSQL .=",特採申請No";
      $strSQL .=",発生_社内コード";
      $strSQL .=",(case when IsNull(是正処置実施確認日,'')<>'' and Len(rTrim(是正処置実施確認日))=8 then CONVERT(datetime, 是正処置実施確認日, 112) else Null end) as 是正処置実施確認日";
      $strSQL .=",備考";
      $strSQL .=" FROM  品質管理データ";
      $strSQL .=" WHERE ";
      $strSQL .= " id > 0";
      if($param_zyouken->年月日S!=""){
        $strSQL .= " AND IsNull(年月日,'') >= :YMD_S";
      }
      if($param_zyouken->年月日E!=""){
        $strSQL .= " AND IsNull(年月日,'') <= :YMD_E";
      }
      if($param_zyouken->顧客コード!=""){
        $strSQL .= " AND 顧客コード = :Kokyaku";
      }
      if($param_zyouken->重大レベルコード!=""){
        $strSQL .= " AND 重大レベルコード = :Zyuudai";
      }
      if($param_zyouken->発注_外注コード!=""){
        $strSQL .= " AND 発注_外注コード = :Gaichyuu";
      }
      if($param_zyouken->不適合製品タグNo!=""){
        $strSQL .= " AND 不適合製品タグNo = :FutekiNo";
      }
      if($param_zyouken->発生_社内コード!=""){
        $strSQL .= " AND 発生_社内コード = :Syain";
      }
      if($param_zyouken->図面番号!=""){
        $strSQL .= " AND 図面番号 like :Hinban";
      }
      if($param_zyouken->セリアルNo!=""){
        $strSQL .= " AND セリアルNo like :SNO";
      }
      if($param_zyouken->不適合区分コード!=""){
        $strSQL .= " AND 不適合区分コード = :FutekigouKubun";
      }
      if($param_zyouken->人的要因コード!=""){
        $strSQL .= " AND 人的要因コード = :Zinteki";
      }
      if($param_zyouken->物的要因コード!=""){
        $strSQL .= " AND 物的要因コード = :Butteki";
      }
      if($param_zyouken->発見コード!=""){
        $strSQL .= " AND 発見コード = :Hakken";
      }
      if($param_zyouken->処置コード!=""){
        $strSQL .= " AND 処置コード = :Syochi";
      }
      if($param_zyouken->是正処置!=""){
        $strSQL .= " AND 是正処置 like :Zeseisyoti";
      }
      if($param_zyouken->是正処置実施確認日!=""){
        $strSQL .= " AND 是正処置実施確認日 = :ZeseiYMD";
      }
      if($param_zyouken->是正処置実施確認日ch!=""){
        // 是正処置実施確認日が未入力
        $strSQL .= " AND  rTrim(IsNull(是正処置実施確認日,''))= ''";
      }
      if($param_zyouken->特採申請No!=""){
        $strSQL .= " AND 特採申請No like :TokusaiNo";
      }
      $strSQL .=" )";
      $strSQL .=" ORDER BY 年月日,不適合製品タグNo,id";
      if($param_select!=""){
        $value = DB::select($strSQL,$param_select);
      }else{
        $value = DB::select($strSQL);
      }
      if(count($value)>0){
        return $this->FnPageSet(1,$value); 
      }else{
        return $this->FnPageSet_Kensaku($param_zyouken,1);
        // // return back()->with(['error', 'データが有りません。'])->withInput();
        // return back()
        // ->withInput()
        // ->with('error', 'データが有りません。');
      }
    }
    
    public function FnWrite(Request $request){
      $MaxData = $request->input('txtMaxData');

      for($cnt = 1 ; $cnt <= $MaxData ; $cnt++){
            $strID = $request->input('txtID'.$cnt);
            $strtxtYMD = $request->input('txtYMD'.$cnt);
            if($strtxtYMD!= ""){
              $strtxtYMD = date("Ymd", strtotime($strtxtYMD));
            }else{
              $strtxtYMD = "";
            }
            $strcmKokyaku =  $request->input('cmKokyaku'.$cnt);
            $strnbKensa = $request->input('nbKensa'.$cnt);
            $strtxtFutekigou = $request->input('txtFutekigou'.$cnt);
            $strcmZyuudai =  $request->input('cmZyuudai'.$cnt);
            $strcmButteki =  $request->input('cmButteki'.$cnt);
            $strcmHakken =  $request->input('cmHakken'.$cnt);
            $strtxtBikou = $request->input('txtBikou'.$cnt);
            $strtxtFutekiNo = $request->input('txtFutekiNo'.$cnt);
            $strcmFutekigouKubun =  $request->input('cmFutekigouKubun'.$cnt);
            $strnbFutekigouSuu = $request->input('nbFutekigouSuu'.$cnt);
            $strtxtGennin = $request->input('txtGennin'.$cnt);
            $strcmSyochi =  $request->input('cmSyochi'.$cnt);
            $strcmZinteki =  $request->input('cmZinteki'.$cnt);
            $strcmGaichyuu =  $request->input('cmGaichyuu'.$cnt);
            $strtxtHinnmei = $request->input('txtHinnmei'.$cnt);
            $strtxtHinban = $request->input('txtHinban'.$cnt);
            $strtxtSNO = $request->input('txtSNO'.$cnt);
            $strtxtZeseisyoti = $request->input('txtZeseisyoti'.$cnt);
            $strtxtKakuNo = $request->input('txtKakuNo'.$cnt);
            $strtxtTokusaiNo = $request->input('txtTokusaiNo'.$cnt);
            $strcmSyain =  $request->input('cmSyain'.$cnt);
            $strtxZeseiYMD = $request->input('txZeseiYMD'.$cnt);
            if($strtxZeseiYMD!= ""){
              $strtxZeseiYMD = date("Ymd", strtotime($strtxZeseiYMD));
            }else{
              $strtxZeseiYMD = "";
            }
            
            if($strID == ""){
              $strID=$this->FnMaxID();
            }

            DB::beginTransaction();
            try {
                // 最初にデータを削除する
                $param = ['id' => $strID];

                $sql  = "DELETE FROM 品質管理データ";
                $sql .= " WHERE id = :id";
                $values = DB::delete($sql, $param);

                $writeRow = [];
                $writeRow['id'] = $strID;
                $writeRow['FutekiNo'] = trim($strtxtFutekiNo);
                $writeRow['YMD'] = $strtxtYMD;
                $writeRow['Kokyaku'] = trim($strcmKokyaku);
                $writeRow['Kensa'] = $strnbKensa;
                $writeRow['Futekigou'] = trim($strtxtFutekigou);
                $writeRow['Zyuudai'] = trim($strcmZyuudai);
                $writeRow['Butteki'] = trim($strcmButteki);
                $writeRow['Hakken'] = trim($strcmHakken);
                $writeRow['FutekigouKubun'] = trim($strcmFutekigouKubun);
                $writeRow['FutekigouSuu'] = $strnbFutekigouSuu;
                $writeRow['Gennin'] = trim($strtxtGennin);
                $writeRow['Syochi'] = trim($strcmSyochi);
                $writeRow['Zinteki'] = trim($strcmZinteki);
                $writeRow['Gaichyuu'] = trim($strcmGaichyuu);
                $writeRow['Hinnmei'] = trim($strtxtHinnmei);
                $writeRow['Hinban'] = trim($strtxtHinban);
                $writeRow['SNO'] = trim($strtxtSNO);
                $writeRow['Zeseisyoti'] = trim($strtxtZeseisyoti);
                $writeRow['KakuNo'] = trim($strtxtKakuNo);
                $writeRow['TokusaiNo'] = trim($strtxtTokusaiNo);
                $writeRow['Syain'] = trim($strcmSyain);
                $writeRow['ZeseiYMD'] = trim($strtxZeseiYMD);
                $writeRow['Bikou'] = trim($strtxtBikou);
                $sql = "INSERT INTO 品質管理データ(" .
                "id".
                ",不適合製品タグNo".
                ",年月日".
                ",顧客コード".
                ",検査数".
                ",不適合内容".
                ",重大レベルコード".
                ",物的要因コード".
                ",発見コード".
                ",不適合区分コード".
                ",不適合数".
                ",原因".
                ",処置コード".
                ",人的要因コード".
                ",発注_外注コード".
                ",品名".
                ",図面番号".
                ",セリアルNo".
                ",是正処置".
                ",各No".
                ",特採申請No".
                ",発生_社内コード".
                ",是正処置実施確認日".
                ",備考".
                ",更新日時".
                ")VALUES(".
                ":id".
                ", :FutekiNo".
                ", :YMD".
                ", :Kokyaku".
                ", :Kensa".
                ", :Futekigou".
                ", :Zyuudai".
                ", :Butteki".
                ", :Hakken".
                ", :FutekigouKubun".
                ", :FutekigouSuu".
                ", :Gennin".
                ", :Syochi".
                ", :Zinteki".
                ", :Gaichyuu".
                ", :Hinnmei".
                ", :Hinban".
                ", :SNO".
                ", :Zeseisyoti".
                ", :KakuNo".
                ", :TokusaiNo".
                ", :Syain".
                ", :ZeseiYMD".
                ", :Bikou".
                ", GETDATE())";       
                DB::insert($sql, $writeRow);              
                DB::commit();
            } catch(\Exception $ex){
                DB::rollBack();
                return response($ex->getMessage());
            }
      }
      // return redirect()->back()->withInput();
      return back()->withInput();     // 送信データがセッション内に格納される
  }

  private function FnPageSet($intK,$Data){
    // 初回のデータを表示する
    if($intK==0){
      $strTitle = "新規";
      $item[] = [
        '行番号' => 1
        ,'DB区分' => 0
        ,'id' => ""
        ,'不適合製品タグNo' => ""
        ,'年月日'  => MZCommon::FnNowDateTimeGet()->format("Y-m-d")
        ,'顧客コード' => ""
        ,'検査数' => "0"
        ,'不適合内容' => ""
        ,'重大レベルコード' => ""
        ,'物的要因コード' => ""
        ,'発見コード' => ""
        ,'不適合区分コード' => ""
        ,'不適合数' => ""
        ,'原因' => ""
        ,'処置コード' => ""
        ,'人的要因コード' => ""
        ,'発注_外注コード' => ""
        ,'品名' => ""
        ,'図面番号' => ""
        ,'セリアルNo' => ""
        ,'是正処置' => ""
        ,'各No' => ""
        ,'特採申請No' => ""
        ,'発生_社内コード' => ""
        ,'是正処置実施確認日' => ""
        ,'備考' => ""
      ];
      $itemvalues = MZCommon::FntoObject($item);
    }else{
      $strTitle = "検索";
      $itemvalues=$Data;
    }

    $dtZyuudai = $this->FnCmbZyuudaiSet();
    $dtSyochi = $this->FnCmbSyochiSet();
    $dtButteki = $this->FnCmbButtekiSet();
    $dtFutekigouKubun = $this->FnCmbFutekigouKubunSet();
    $dtZinteki = $this->FnCmbZintekiSet();
    $dtHakken = $this->FnCmbHakkenSet();
    $dtKokyaku = $this->FnCmbKokyakuSet();
    $dtGaichyuu = $this->FnCmbGaichyuuSet();
    $dtSyain = $this->FnCmbSyainSet();
    //※注意、TabIndex設定用、項目数が変わったらここも変更する。
    $intKSuu = 22;
    //-----------------------------------------------------
    $intMaxData = count($itemvalues);

    return view('project.mzwp0010', 
                compact('strTitle'
                ,'intMaxData'
                ,'itemvalues'
                , 'dtKokyaku'
                , 'dtGaichyuu'
                , 'dtSyain'
                , 'dtZyuudai'
                , 'dtSyochi'
                , 'dtButteki'
                , 'dtFutekigouKubun'
                , 'dtZinteki'
                , 'dtHakken'
                , 'intKSuu'
              ));
  }

  private function FnZyouken_array($request){
    // 条件変数設定
    $array_zyouken['年月日S'] = $request->input('txYMD_S');
    $array_zyouken['年月日E'] = $request->input('txYMD_E');
    $array_zyouken['顧客コード'] = $request->input('cmKokyaku');
    $array_zyouken['重大レベルコード'] = $request->input('cmZyuudai');
    $array_zyouken['発注_外注コード'] = $request->input('cmGaichyuu');
    $array_zyouken['不適合製品タグNo'] = $request->input('txtFutekiNo');
    $array_zyouken['発生_社内コード'] = $request->input('cmSyain');
    $array_zyouken['図面番号'] = $request->input('txtHinban');
    $array_zyouken['セリアルNo'] = $request->input('txtSNO');
    $array_zyouken['不適合区分コード'] = $request->input('cmFutekigouKubun');
    $array_zyouken['人的要因コード'] = $request->input('cmZinteki');
    $array_zyouken['物的要因コード'] = $request->input('cmButteki');
    $array_zyouken['発見コード'] = $request->input('cmHakken');
    $array_zyouken['処置コード'] = $request->input('cmSyochi');
    $array_zyouken['是正処置'] = $request->input('txtZeseisyoti');
    $array_zyouken['是正処置実施確認日'] = $request->input('txZeseiYMD');
    $array_zyouken['是正処置実施確認日ch'] = $request->input('chZeseiYMD');
    $array_zyouken['特採申請No'] = $request->input('txtTokusaiNo');

    return $array_zyouken;
  }

  private function FnZyouken_array_New(){
    // 条件変数設定
    $array_zyouken['年月日S'] = "";
    $array_zyouken['年月日E'] = "";
    $array_zyouken['顧客コード'] = "";
    $array_zyouken['重大レベルコード'] = "";
    $array_zyouken['発注_外注コード'] = "";
    $array_zyouken['不適合製品タグNo'] = "";
    $array_zyouken['発生_社内コード'] = "";
    $array_zyouken['図面番号'] = "";
    $array_zyouken['セリアルNo'] = "";
    $array_zyouken['不適合区分コード'] = "";
    $array_zyouken['人的要因コード'] = "";
    $array_zyouken['物的要因コード'] = "";
    $array_zyouken['発見コード'] = "";
    $array_zyouken['処置コード'] = "";
    $array_zyouken['是正処置'] = "";
    $array_zyouken['是正処置実施確認日'] = "";
    $array_zyouken['是正処置実施確認日ch'] = "";
    $array_zyouken['特採申請No'] = "";

    return $array_zyouken;
  }

  private function FnPageSet_Kensaku($itemvalues,$intDataKubun){
    // 検索画面を表示する
    $strTitle = "検索";
    $dtZyuudai = $this->FnCmbZyuudaiSet();
    $dtSyochi = $this->FnCmbSyochiSet();
    $dtButteki = $this->FnCmbButtekiSet();
    $dtFutekigouKubun = $this->FnCmbFutekigouKubunSet();
    $dtZinteki = $this->FnCmbZintekiSet();
    $dtHakken = $this->FnCmbHakkenSet();
    $dtKokyaku = $this->FnCmbKokyakuSet();
    $dtGaichyuu = $this->FnCmbGaichyuuSet();
    $dtSyain = $this->FnCmbSyainSet();
    
    return view('project.mzwp0011', 
                compact('strTitle'
                , 'intDataKubun'
                , 'itemvalues'
                , 'dtKokyaku'
                , 'dtGaichyuu'
                , 'dtSyain'
                , 'dtZyuudai'
                , 'dtSyochi'
                , 'dtButteki'
                , 'dtFutekigouKubun'
                , 'dtZinteki'
                , 'dtHakken'
              ));
  }
    private function FnCmbKokyakuSet(){
      //顧客をセットする
      $strSQL = "(";
      $strSQL .=" SELECT";
      $strSQL .=" 得意先コード as code";
      $strSQL .=",得意先名 as name";
      $strSQL .=" FROM  得意先マスタ AS 得意先";
      $strSQL .=" WHERE ISNULL(検査室使用フラグ,0) = 1";
      $strSQL .=" )";
      $strSQL .=" ORDER BY (case when IsNull(検査室並び順,0)=0 then 99999 else 検査室並び順 end)";
      $strSQL .=",得意先コード";
      $value = DB::select($strSQL);

      return $value;
  }
  private function FnCmbGaichyuuSet(){
    //外注先をセットする
    $strSQL = "(";
    $strSQL .=" SELECT";
    $strSQL .=" 外注先コード as code";
    $strSQL .=",外注先名 as name";
    $strSQL .=" FROM  外注先マスタ AS 外注先";
    $strSQL .=" WHERE ISNULL(検査室使用フラグ,0) = 1";
    $strSQL .=" )";
    $strSQL .=" ORDER BY (case when IsNull(検査室並び順,0)=0 then 99999 else 検査室並び順 end)";
    $strSQL .=",外注先コード";
    $value = DB::select($strSQL);

    return $value;
}
private function FnCmbSyainSet(){
  //社員をセットする
  $strSQL = "(";
  $strSQL .=" SELECT";
  $strSQL .=" 社員コード as code";
  $strSQL .=",社員名 as name";
  $strSQL .=" FROM  社員マスタ AS 社員";
  $strSQL .=" WHERE ISNULL(検査室使用フラグ,0) = 1";
  $strSQL .=" )";
  $strSQL .=" ORDER BY (case when IsNull(検査室並び順,0)=0 then 99999 else 検査室並び順 end)";
  $strSQL .=",社員コード";
  $value = DB::select($strSQL);

  return $value;
}

  private function FnCmbZyuudaiSet(){
          //品質管理_重大レベルマスタをセットする
          $strSQL = "(";
          $strSQL .=" SELECT";
          $strSQL .=" コード as code";
          $strSQL .=",名称 as name";
          $strSQL .=" FROM 品質管理_重大レベルマスタ";
          $strSQL .=" )";
          $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
          $value = DB::select($strSQL);

          return $value;
      }
    private function FnCmbSyochiSet(){
          // 品質管理_処置マスタをセットする
          $strSQL = "(";
          $strSQL .=" SELECT";
          $strSQL .=" コード as code";
          $strSQL .=",名称 as name";
          $strSQL .=" FROM  品質管理_処置マスタ";
          $strSQL .=" )";
          $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
          $value = DB::select($strSQL);

          return $value;
      }
    private function FnCmbButtekiSet(){
          //品質管理_物的要因マスタをセットする
          $strSQL = "(";
          $strSQL .=" SELECT";
          $strSQL .=" コード as code";
          $strSQL .=",名称 as name";
          $strSQL .=" FROM  品質管理_物的要因マスタ";
          $strSQL .=" )";
          $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
          $value = DB::select($strSQL);

          return $value;
      }
      private function FnCmbFutekigouKubunSet(){
        //品質管理_不適合区分マスタをセットする
        $strSQL = "(";
        $strSQL .=" SELECT";
        $strSQL .=" コード as code";
        $strSQL .=",名称 as name";
        $strSQL .=" FROM  品質管理_不適合区分マスタ";
        $strSQL .=" )";
        $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
        $value = DB::select($strSQL);

        return $value;
    }
    private function FnCmbZintekiSet(){
  
          //品質管理_人的要因マスタをセットする
          $strSQL = "(";
          $strSQL .=" SELECT";
          $strSQL .=" コード as code";
          $strSQL .=",名称 as name";
          $strSQL .=" FROM  品質管理_人的要因マスタ";
          $strSQL .=" )";
          $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
          $value = DB::select($strSQL);

          return $value;
      }
    private function FnCmbHakkenSet(){
          //品質管理_発見マスタをセットする
          $strSQL = "(";
          $strSQL .=" SELECT";
          $strSQL .=" コード as code";
          $strSQL .=",名称 as name";
          $strSQL .=" FROM  品質管理_発見マスタ";
          $strSQL .=" )";
          $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
          $value = DB::select($strSQL);

          return $value;
      }

      private function FnMaxID(){
        //idの最大＋１を取得する。
        $maxNo = 0;
        $strSQL = "SELECT MAX(id) AS manNo FROM 品質管理データ";
        $value = DB::select($strSQL);
        if($value!= null){
            $maxNo = $value[0]->manNo;
        }
        $maxNo += 1;

        return $maxNo;
    }

    /*-----------
    入力チェック処理
    -----------*/
    public function FnHinban_check(Request $req){
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