<?php

namespace App\Http\Controllers\project;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // DB ファサードを use する
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Facades\AlefCommon;
use App\Facades\MZCommon;
use Carbon\Carbon;
use Illuminate\Support\Collection; //foreachで使用
class mzwp0040Controller extends Controller
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
       if($param_zyouken->種類別!=""){
        $intCheck = 1;
        $array['Kanrisyubetu'] = $param_zyouken->種類別;
      }
      if($param_zyouken->管理番号!=""){
        $intCheck = 1;
        $array['KanriBangou'] = "%".$param_zyouken->管理番号."%";
      }
      if($param_zyouken->名称型式!=""){
        $intCheck = 1;
        $array['MeisyouKatasiki'] = "%".$param_zyouken->名称型式."%";
      }
      if($param_zyouken->製造番号!=""){
        $intCheck = 1;
        $array['SeizouBangou'] = "%".$param_zyouken->製造番号."%";
      }
      if($param_zyouken->校正区分!=""){
        $intCheck = 1;
        $array['KouseiKubun'] = $param_zyouken->校正区分;
      }
      if($param_zyouken->借用者!=""){
        $intCheck = 1;
        $array['Syakuyou'] = $param_zyouken->借用者;
      }
      if($param_zyouken->保管場所!=""){
        $intCheck = 1;
        $array['Hokan'] = $param_zyouken->保管場所;
      }
      if($param_zyouken->測定範囲!=""){
        $intCheck = 1;
        $array['SokuteiHanni'] = $param_zyouken->測定範囲;
      }
      if($param_zyouken->メーカ名!=""){
        $intCheck = 1;
        $array['Maker'] = $param_zyouken->メーカ名;
      }

      $strtxYMD = $param_zyouken->登録日;
      if($strtxYMD!= ""){
        $intCheck = 1;
        $strtxYMD = date("Ymd", strtotime($strtxYMD));
        $array['TourokuYMD'] = $strtxYMD;
      }
      // if($param_zyouken->登録日op!=""){
      //   $array['opTourokuYMD'] = $param_zyouken->登録日op;
      // }

      $strtxYMD = $param_zyouken->次回検定日;
      if($strtxYMD!= ""){
        $intCheck = 1;
        $strtxYMD = date("Ymd", strtotime($strtxYMD));
        $array['ZikaiKentei'] = $strtxYMD;
      }
      // if($param_zyouken->次回検定日op!=""){
      //   $array['opZikaiKentei'] = $param_zyouken->次回検定日op;
      // }

      $strtxYMD = $param_zyouken->検定実施日;
      if($strtxYMD!= ""){
        $intCheck = 1;
        $strtxYMD = date("Ymd", strtotime($strtxYMD));
        $array['KenteiZissibi'] = $strtxYMD;
      }
      
      // if($param_zyouken->検定実施日op!=""){
      //   $array['opKenteiZissibi'] = $param_zyouken->検定実施日op;
      // }
      if($intCheck!=0){
        $param_select = $array;
      }else{
        $param_select = "";
      }
      
      $strSQL = "(";
      $strSQL .=" SELECT";
      $strSQL .=" ROW_NUMBER() OVER(ORDER BY 管理番号,id) as 行番号";
      $strSQL .=",0 as DB区分";
      $strSQL .=",id";
      $strSQL .=",管理種別コード";
      $strSQL .=",名称型式";
      $strSQL .=",管理番号";
      $strSQL .=",製造番号";
      $strSQL .=",測定範囲";
      $strSQL .=",メーカーコード";
      $strSQL .=",(case when IsNull(登録日,'')<>'' and Len(rTrim(登録日))=8 then CONVERT(datetime, 登録日, 112) else Null end) as 登録日";
      $strSQL .=",借用者コード";
      $strSQL .=",保管場所コード";
      $strSQL .=",校正区分コード";
      $strSQL .=",用途区分コード";
      $strSQL .=",(case when IsNull(次回検定日,'')<>'' and Len(rTrim(次回検定日))=8 then CONVERT(datetime, 次回検定日, 112) else Null end) as 次回検定日";
      $strSQL .=",(case when IsNull(検定実施日,'')<>'' and Len(rTrim(検定実施日))=8 then CONVERT(datetime, 検定実施日, 112) else Null end) as 検定実施日";
      $strSQL .=",備考";
      $strSQL .=" FROM  計測器データ";
      $strSQL .=" WHERE ";
      $strSQL .= " id > 0";
      if($param_zyouken->種類別!=""){
        $strSQL .= " AND IsNull(管理種別コード,'') = :Kanrisyubetu";
      }
      if($param_zyouken->管理番号!=""){
        $strSQL .= " AND IsNull(管理番号,'') Like :KanriBangou";
      }
      if($param_zyouken->名称型式!=""){
        $strSQL .= " AND IsNull(名称型式,'') Like :MeisyouKatasiki";
      }
      if($param_zyouken->製造番号!=""){
        $strSQL .= " AND IsNull(製造番号,'') Like :SeizouBangou";
      }
      if($param_zyouken->校正区分!=""){
        $strSQL .= " AND IsNull(校正区分コード,'') = :KouseiKubun";
      }
      if($param_zyouken->借用者!=""){
        $strSQL .= " AND IsNull(借用者コード,'') = :Syakuyou";
      }
      if($param_zyouken->保管場所!=""){
        $strSQL .= " AND IsNull(保管場所コード,'') = :Hokan";
      }
      if($param_zyouken->測定範囲!=""){
        $strSQL .= " AND IsNull(測定範囲,'') = :SokuteiHanni";
      }
      if($param_zyouken->メーカ名!=""){
        $strSQL .= " AND IsNull(メーカーコード,'') = :Maker";
      }
      if($param_zyouken->登録日!=""){
        if(Trim($param_zyouken->登録日op)=="1"){
          $strSQL .= " AND IsNull(登録日,'') = :TourokuYMD";
        }elseif(Trim($param_zyouken->登録日op)=="2"){
          $strSQL .= " AND IsNull(登録日,'') >= :TourokuYMD";
        }elseif(Trim($param_zyouken->登録日op)=="3"){
          $strSQL .= " AND IsNull(登録日,'') <= :TourokuYMD";
        }
      }
      if($param_zyouken->次回検定日!=""){
        if($param_zyouken->次回検定日op=="1"){
          $strSQL .= " AND IsNull(次回検定日,'') = :ZikaiKentei";
        }elseif($param_zyouken->次回検定日op=="2"){
          $strSQL .= " AND IsNull(次回検定日,'') >= :ZikaiKentei";
        }elseif($param_zyouken->次回検定日op=="3"){
          $strSQL .= " AND IsNull(次回検定日,'') <= :ZikaiKentei";
        }
      }
      if($param_zyouken->検定実施日!=""){
        if($param_zyouken->検定実施日op=="1"){
          $strSQL .= " AND IsNull(検定実施日,'') = :KenteiZissibi";
        }elseif($param_zyouken->検定実施日op=="2"){
          $strSQL .= " AND IsNull(検定実施日,'') >= :KenteiZissibi";
        }elseif($param_zyouken->検定実施日op=="3"){
          $strSQL .= " AND IsNull(検定実施日,'') <= :KenteiZissibi";
        }
      }
      if(trim($param_zyouken->使用停止廃却処分品ch)==""){
        $strSQL .= " AND (IsNull(借用者コード,'') = ''";
        $strSQL .= " OR IsNull(借用者コード,'') IN (select コード from 計測器_借用者マスタ where IsNull(検索除外フラグ,0)=0))";
      }
      $strSQL .=" )";
      $strSQL .=" ORDER BY 管理番号,id";
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
            $strcmKanrisyubetu = $request->input('cmKanrisyubetu'.$cnt);
            $strtxtMeisyouKatasiki =  $request->input('txtMeisyouKatasiki'.$cnt);
            $strtxtSokuteiHanni =  $request->input('txtSokuteiHanni'.$cnt);
            $strcmMaker =  $request->input('cmMaker'.$cnt);
            $strtxtTourokuYMD = $request->input('txtTourokuYMD'.$cnt);
            if($strtxtTourokuYMD!= ""){
              $strtxtTourokuYMD = date("Ymd", strtotime($strtxtTourokuYMD));
            }else{
              $strtxtTourokuYMD = "";
            }
            $strcmSyakuyou =  $request->input('cmSyakuyou'.$cnt);
            $strcmKouseiKubun =  $request->input('cmKouseiKubun'.$cnt);
            $strcmYoutoKubun =  $request->input('cmYoutoKubun'.$cnt);
            $strtxtZikaiKentei = $request->input('txtZikaiKentei'.$cnt);
            if($strtxtZikaiKentei!= ""){
              $strtxtZikaiKentei = date("Ymd", strtotime($strtxtZikaiKentei));
            }else{
              $strtxtZikaiKentei = "";
            }
            $strtxtBikou =  $request->input('txtBikou'.$cnt);

            $strtxtKanriBangou = $request->input('txtKanriBangou'.$cnt);
            if($strtxtKanriBangou==""){
              $strtxtKanriBangou=$this->FnKanriBangou_New($strcmKanrisyubetu);
            }else{
              // 管理種別が変更になった場合番号を振り直さないといけない
              $intK =0;
              $intK =$this->FnKanriChange($strID,$strcmKanrisyubetu);
              if($intK==1){
                // 変更有
                $strtxtKanriBangou=$this->FnKanriBangou_New($strcmKanrisyubetu);
              }
            }

            $strtxtSeizouBangou =  $request->input('txtSeizouBangou'.$cnt);
            $strcmHokan =  $request->input('cmHokan'.$cnt);
            $strtxtKenteiZissibi = $request->input('txtKenteiZissibi'.$cnt);
            if($strtxtKenteiZissibi!= ""){
              $strtxtKenteiZissibi = date("Ymd", strtotime($strtxtKenteiZissibi));
            }else{
              $strtxtKenteiZissibi = "";
            }
            
            if($strID == ""){
              $strID=$this->FnMaxID();
            }

            DB::beginTransaction();
            try {
                // 最初にデータを削除する
                $param = ['id' => $strID];

                $sql  = "DELETE FROM 計測器データ";
                $sql .= " WHERE id = :id";
                $values = DB::delete($sql, $param);

                $writeRow = [];
                $writeRow['id'] = $strID;
                $writeRow['Kanrisyubetu'] = trim($strcmKanrisyubetu);
                $writeRow['MeisyouKatasiki'] = trim($strtxtMeisyouKatasiki);
                $writeRow['SokuteiHanni'] = trim($strtxtSokuteiHanni);
                $writeRow['Maker'] = trim($strcmMaker);
                $writeRow['TourokuYMD'] = trim($strtxtTourokuYMD);
                $writeRow['Syakuyou'] = trim($strcmSyakuyou);
                $writeRow['KouseiKubun'] = trim($strcmKouseiKubun);
                $writeRow['YoutoKubun'] = trim($strcmYoutoKubun);
                $writeRow['ZikaiKentei'] = trim($strtxtZikaiKentei);
                $writeRow['Bikou'] = trim($strtxtBikou);
                $writeRow['KanriBangou'] = trim($strtxtKanriBangou);
                $writeRow['SeizouBangou'] = trim($strtxtSeizouBangou);
                $writeRow['Hokan'] = trim($strcmHokan);
                $writeRow['KenteiZissibi'] = trim($strtxtKenteiZissibi);

                $sql = "INSERT INTO 計測器データ(" .
                "id".
                ",管理種別コード".
                ",名称型式".
                ",管理番号".
                ",製造番号".
                ",測定範囲".
                ",メーカーコード".
                ",登録日".
                ",借用者コード".
                ",保管場所コード".
                ",校正区分コード".
                ",用途区分コード".
                ",次回検定日".
                ",検定実施日".
                ",備考".
                ",更新日時".
                ")VALUES(".
                ":id".
                ", :Kanrisyubetu".
                ", :MeisyouKatasiki".
                ", :KanriBangou".
                ", :SeizouBangou".
                ", :SokuteiHanni".
                ", :Maker".
                ", :TourokuYMD".
                ", :Syakuyou".
                ", :Hokan".
                ", :KouseiKubun".
                ", :YoutoKubun".
                ", :ZikaiKentei".
                ", :KenteiZissibi".
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
        ,'管理種別コード' => ""
        ,'名称型式' => ""
        ,'管理番号' => ""
        ,'製造番号' => ""
        ,'測定範囲' => ""
        ,'メーカーコード' => ""
        ,'登録日' => ""
        ,'借用者コード' => ""
        ,'保管場所コード' => ""
        ,'校正区分コード' => ""
        ,'用途区分コード' => ""
        ,'次回検定日' => ""
        ,'検定実施日' => ""
        ,'備考' => ""
      ];
      $itemvalues = MZCommon::FntoObject($item);
    }else{
      $strTitle = "検索";
      $itemvalues=$Data;
    }

    $dtKanrisyubetu = $this->FnCmbKanrisyubetuSet();
    $dtMaker = $this->FnCmbMakerSet();
    $dtSyakuyou = $this->FnCmbSyakuyouSet();
    $dtKouseiKubun = $this->FnCmbKouseiKubunSet();
    $dtYoutoKubun = $this->FnCmbYoutoKubunSet();
    $dtHokan = $this->FnCmbHokanSet();
    //※注意、TabIndex設定用、項目数が変わったらここも変更する。
    $intKSuu = 13;
    //-----------------------------------------------------
    $intMaxData = count($itemvalues);

    return view('project.mzwp0040', 
                compact('strTitle'
                ,'intMaxData'
                ,'itemvalues'
                , 'dtKanrisyubetu'
                , 'dtMaker'
                , 'dtSyakuyou'
                , 'dtKouseiKubun'
                , 'dtYoutoKubun'
                , 'dtHokan'
                , 'intKSuu'
              ));
  }

  private function FnZyouken_array($request){
    // 条件変数設定
    $array_zyouken['種類別'] = $request->input('cmKanrisyubetu');
    $array_zyouken['管理番号'] = $request->input('txtKanriBangou');
    $array_zyouken['名称型式'] = $request->input('txtMeisyouKatasiki');
    $array_zyouken['製造番号'] = $request->input('txtSeizouBangou');
    $array_zyouken['校正区分'] = $request->input('cmKouseiKubun');
    $array_zyouken['借用者'] = $request->input('cmSyakuyou');
    $array_zyouken['保管場所'] = $request->input('cmHokan');
    $array_zyouken['測定範囲'] = $request->input('txtSokuteiHanni');
    $array_zyouken['メーカ名'] = $request->input('cmMaker');
    $array_zyouken['登録日'] = $request->input('txtTourokuYMD');
    $array_zyouken['登録日op'] = $request->input('opTourokuYMD');
    $array_zyouken['次回検定日'] = $request->input('txtZikaiKentei');
    $array_zyouken['次回検定日op'] = $request->input('opZikaiKentei');
    $array_zyouken['検定実施日'] = $request->input('txtKenteiZissibi');
    $array_zyouken['検定実施日op'] = $request->input('opKenteiZissibi');
    $array_zyouken['使用停止廃却処分品ch'] = $request->input('chTeisi');

    return $array_zyouken;
  }

  private function FnZyouken_array_New(){
    // 条件変数設定
    $array_zyouken['種類別'] = "";
    $array_zyouken['管理番号'] = "";
    $array_zyouken['名称型式'] = "";
    $array_zyouken['製造番号'] = "";
    $array_zyouken['校正区分'] = "";
    $array_zyouken['借用者'] = "";
    $array_zyouken['保管場所'] = "";
    $array_zyouken['測定範囲'] = "";
    $array_zyouken['メーカ名'] = "";
    $array_zyouken['登録日'] = "";
    $array_zyouken['登録日op'] = "1";
    $array_zyouken['次回検定日'] = "";
    $array_zyouken['次回検定日op'] = "1";
    $array_zyouken['検定実施日'] = "";
    $array_zyouken['検定実施日op'] = "1";
    $array_zyouken['使用停止廃却処分品ch'] = "";

    return $array_zyouken;
  }

  private function FnPageSet_Kensaku($itemvalues,$intDataKubun){
    // 検索画面を表示する
    $strTitle = "検索";
    $dtKanrisyubetu = $this->FnCmbKanrisyubetuSet();
    $dtKouseiKubun = $this->FnCmbKouseiKubunSet();
    $dtSyakuyou = $this->FnCmbSyakuyouSet();
    $dtHokan = $this->FnCmbHokanSet();
    $dtMaker = $this->FnCmbMakerSet();
    $dtKensuu = $this->FnCmbKensuuSet();

    return view('project.mzwp0041', 
                compact('strTitle'
                , 'intDataKubun'
                , 'itemvalues'
                , 'dtKanrisyubetu'
                , 'dtKouseiKubun'
                , 'dtSyakuyou'
                , 'dtHokan'
                , 'dtMaker'
                , 'dtKensuu'
              ));
  }

  private function FnKanriChange($strID,$strKansyu){
    // 管理種別コードを取得
    if(trim($strID)==""||trim($strKansyu)==""){
      // 空白
      return 1;
    }
    $data ="";
    
    $strSQL = "(";
    $strSQL .= "SELECT 管理種別コード";
    $strSQL .= " FROM 計測器データ";
    $strSQL .= " WHERE id=".$strID."";
    $strSQL .= " AND 管理種別コード='".$strKansyu."'";
    $strSQL .= ")";
    $value = DB::select($strSQL);
    if(count($value)>0){
      // idと管理種別の組み合わせでデータが有れば変更の必要はない
      return 0;
    }else{
      // データが無ければ変更する必要がある。
      return 1;
    }
  }

  private function FnKanriBangou_New($strKansyu){
    // 管理番号連番(教育の管理番号はED-PL固定)
    $strKB = $this->FnKanriHead_Set($strKansyu);
    if($strKB==""){
      // 片方でも空白なら作らない
      return "";
    }
    $strBan = $strKB;
    $strNewBangou = "";
    $blC=false;
    $intM = AlefCommon::null_i(AlefCommon::GetSystemData($strBan, "計測器_管理番号カウント"));

    do{
        If($intM >= 9999){
          $intM = 1;
        }Else{
          $intM += 1;
        }

        $strNewBangou = $strBan."-".sprintf('%04d', $intM);

        $strSQL = "(";
        $strSQL .= "SELECT 管理番号";
        $strSQL .= " FROM 計測器データ";
        $strSQL .= " WHERE 管理番号='".$strNewBangou."'";
        $strSQL .= ")";
        $value = DB::select($strSQL);
        // info($strSQL);
        if(count($value)<=0){
          $blC=true;
        }
        // info($blC);
        // unset($value);//変数クリア
    }While ($blC = False);

    AlefCommon::PutSystemData($strBan, "計測器_管理番号カウント", strval($intM));

    return $strNewBangou;

  }

  private function FnKanriHead_Set($strDai){
    // 管理種別の管理コードを取得
    if($strDai==""){
      // 空白
      return "";
    }
    $data ="";

    $strSQL = "(";
    $strSQL .= "SELECT 管理番号コード";
    $strSQL .= " FROM 計測器_管理種別マスタ";
    $strSQL .= " WHERE コード='".$strDai."'";
    $strSQL .= ")";
    $value = DB::select($strSQL);
    if(count($value)>0){
      $data = $value[0]->管理番号コード;
    }

    return $data;

  }

      private function FnCmbKanrisyubetuSet(){
        // 計測器_管理種別マスタをセットする
        $strSQL = "(";
        $strSQL .=" SELECT";
        $strSQL .=" コード as code";
        $strSQL .=",名称 as name";
        $strSQL .=" FROM  計測器_管理種別マスタ";
        $strSQL .=" )";
        $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
        $value = DB::select($strSQL);

        return $value;
    }
    private function FnCmbMakerSet(){
      //計測器_メーカ名マスタをセットする
      $strSQL = "(";
      $strSQL .=" SELECT";
      $strSQL .=" コード as code";
      $strSQL .=",名称 as name";
      $strSQL .=" FROM  計測器_メーカ名マスタ";
      $strSQL .=" )";
      $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
      $value = DB::select($strSQL);

      return $value;
  }
    private function FnCmbSyakuyouSet(){
      // 計測器_借用者マスタをセットする
      $strSQL = "(";
      $strSQL .=" SELECT";
      $strSQL .=" コード as code";
      $strSQL .=",名称 as name";
      $strSQL .=" FROM  計測器_借用者マスタ";
      $strSQL .=" )";
      $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
      $value = DB::select($strSQL);

      return $value;
  }
  private function FnCmbKouseiKubunSet(){
    // 計測器_校正区分マスタをセットする
    $strSQL = "(";
    $strSQL .=" SELECT";
    $strSQL .=" コード as code";
    $strSQL .=",名称 as name";
    $strSQL .=" FROM  計測器_校正区分マスタ";
    $strSQL .=" )";
    $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
    $value = DB::select($strSQL);

    return $value;
  }
  private function FnCmbYoutoKubunSet(){
    //計測器_用途区分マスタをセットする
    $strSQL = "(";
    $strSQL .=" SELECT";
    $strSQL .=" コード as code";
    $strSQL .=",名称 as name";
    $strSQL .=" FROM  計測器_用途区分マスタ";
    $strSQL .=" )";
    $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
    $value = DB::select($strSQL);

    return $value;
  }
    private function FnCmbHokanSet(){
      //計測器_保管場所マスタをセットする
      $strSQL = "(";
      $strSQL .=" SELECT";
      $strSQL .=" コード as code";
      $strSQL .=",名称 as name";
      $strSQL .=" FROM  計測器_保管場所マスタ";
      $strSQL .=" )";
      $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
      $value = DB::select($strSQL);

      return $value;
  }
  private function FnCmbKensuuSet(){
    //検定期限までの件数をセットする
    $c = Collection::times(13);
    $strStart = "CONVERT(nvarchar, DATEADD(day, 1, EOMONTH(GETDATE(), -1)))";//当月1日(当月は動かない)
    $strEnd = "";
    $strSQL = "";
    foreach($c as $intI) {
      $intI2 = $intI-1;

      if($intI==1){
        $strEnd = "CONVERT(nvarchar, EOMONTH(GETDATE(), 0))";//当月末
      }else{
        // $strStart = "CONVERT(nvarchar, EOMONTH(DATEADD(month,-".$intI2.", GETDATE()), -1))";//～月1日
        $strEnd = "CONVERT(nvarchar, EOMONTH(DATEADD(month,".$intI2.",DATEADD(day, 1, EOMONTH(GETDATE(), -1)))))";//当月末
        $strSQL .= "UNION ALL";
      }
            
      $strSQL .= "(";
      $strSQL .=" SELECT ".$intI2." as 区分";
      $strSQL .=",COUNT(*) as 件数";
      $strSQL .=",'～".sprintf("%2d", $intI2)."ケ月' as 項目";
      $strSQL .=",".$strStart.",".$strEnd;
      $strSQL .=" FROM 計測器データ";
      $strSQL .=" WHERE 次回検定日 between ".$strStart." and ".$strEnd;
      $strSQL .=" )";
  }
    // info($strSQL);
    $value = DB::select($strSQL);

    return $value;
}
 
  private function FnMaxID(){
        //idの最大＋１を取得する。
        $maxNo = 0;
        $strSQL = "SELECT MAX(id) AS manNo FROM 計測器データ";
        $value = DB::select($strSQL);
        if($value!= null){
            $maxNo = $value[0]->manNo;
        }
        $maxNo += 1;

        return $maxNo;
  }

}