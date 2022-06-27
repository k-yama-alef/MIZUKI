<?php

namespace App\Http\Controllers\project;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // DB ファサードを use する
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Facades\AlefCommon;
use App\Facades\MZCommon;
use Carbon\Carbon;
class mzwp0030Controller extends Controller
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
      if($param_zyouken->題目!=""){
        $intCheck = 1;
        $array['Daimoku'] = "%".$param_zyouken->題目."%";
      }
      if($param_zyouken->文書番号!=""){
        $intCheck = 1;
        $array['BunsyoBangou'] = "%".$param_zyouken->文書番号."%";
      }
      if($param_zyouken->大分類!=""){
        $intCheck = 1;
        $array['Daibunnrui'] = $param_zyouken->大分類;
      }
      if($param_zyouken->小分類!=""){
        $intCheck = 1;
        $array['Syoubunnrui'] = $param_zyouken->小分類;
      }
      if($param_zyouken->保管期限!=""){
        $intCheck = 1;
        $array['HokanYMD'] = $param_zyouken->保管期限;
      }
      if($param_zyouken->作成者!=""){
        $intCheck = 1;
        $array['Sakuseisya'] = $param_zyouken->作成者;
      }
      if($param_zyouken->重要区分!=""){
        $intCheck = 1;
        $array['Zyuuyou'] = $param_zyouken->重要区分;
      }
      if($param_zyouken->キーワード!=""){
        $intCheck = 1;
        $array['KeyZ1'] = $param_zyouken->キーワード;
        $array['KeyZ2'] = $param_zyouken->キーワード;
        $array['KeyZ3'] = $param_zyouken->キーワード;
        $array['KeyZ4'] = $param_zyouken->キーワード;
        $array['KeyZ5'] = $param_zyouken->キーワード;
        $array['KeyZ6'] = $param_zyouken->キーワード;
      }
      if($param_zyouken->作成年!=""){
        $intCheck = 1;
        $array['YYYY_S'] = $param_zyouken->作成年."0101";
        $array['YYYY_E'] = $param_zyouken->作成年."1231";
      }
      
      if($intCheck!=0){
        $param_select = $array;
      }else{
        $param_select = "";
      }
      
      $strSQL = "(";
      $strSQL .=" SELECT";
      $strSQL .=" ROW_NUMBER() OVER(ORDER BY 作成年月日,id) as 行番号";
      $strSQL .=",0 as DB区分";
      $strSQL .=",id";
      $strSQL .=",文書番号";
      $strSQL .=",登録者区分コード";
      $strSQL .=",(case when IsNull(作成年月日,'')<>'' and Len(rTrim(作成年月日))=8 then CONVERT(datetime, 作成年月日, 112) else Null end) as 作成年月日";
      $strSQL .=",作成者コード";
      $strSQL .=",形態コード";
      $strSQL .=",題目";
      $strSQL .=",概要";
      $strSQL .=",大分類コード";
      $strSQL .=",小分類コード";
      $strSQL .=",保管先コード";
      $strSQL .=",版番号";
      $strSQL .=",キーワード1";
      $strSQL .=",キーワード2";
      $strSQL .=",キーワード3";
      $strSQL .=",キーワード4";
      $strSQL .=",キーワード5";
      $strSQL .=",キーワード6";
      $strSQL .=",(case when IsNull(保管期限,'')<>'' and Len(rTrim(保管期限))=8 then CONVERT(datetime, 保管期限, 112) else Null end) as 保管期限";
      $strSQL .=",入手区分コード";
      $strSQL .=",重要区分コード";
      $strSQL .=",備考";
      $strSQL .=" FROM  文書管理データ";
      $strSQL .=" WHERE ";
      $strSQL .= " id > 0";
      if($param_zyouken->題目!=""){
        $strSQL .= " AND IsNull(題目,'') Like :Daimoku";
      }
      if($param_zyouken->文書番号!=""){
        $strSQL .= " AND IsNull(文書番号,'') Like :BunsyoBangou";
      }
      if($param_zyouken->大分類!=""){
        $strSQL .= " AND IsNull(大分類コード,'') = :Daibunnrui";
      }
      if($param_zyouken->小分類!=""){
        $strSQL .= " AND IsNull(小分類コード,'') = :Syoubunnrui";
      }
      if($param_zyouken->保管期限!=""){
        $strSQL .= " AND IsNull(保管期限,'') = :HokanYMD";
      }
      if($param_zyouken->作成者!=""){
        $strSQL .= " AND IsNull(作成者コード,'') = :Sakuseisya";
      }
      if($param_zyouken->重要区分!=""){
        $strSQL .= " AND IsNull(重要区分コード,'') = :Zyuuyou";
      }
      if($param_zyouken->キーワード!=""){
        $strSQL .= " AND (IsNull(キーワード1,'') = :KeyZ1 or IsNull(キーワード2,'') = :KeyZ2 or IsNull(キーワード3,'') = :KeyZ3 or IsNull(キーワード4,'') = :KeyZ4 or IsNull(キーワード5,'') = :KeyZ5 or IsNull(キーワード6,'') = :KeyZ6)";
      }
      if($param_zyouken->作成年!=""){
        $strSQL .= " AND (IsNull(作成年月日,'') between :YYYY_S and :YYYY_E)";
      }
    
      $strSQL .=" )";
      $strSQL .=" ORDER BY 作成年月日,id";
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
            $strcmTourokusya =  $request->input('cmTourokusya'.$cnt);
            $strtxtSakuseiYMD = $request->input('txtSakuseiYMD'.$cnt);
            if($strtxtSakuseiYMD!= ""){
              $strtxtSakuseiYMD = date("Ymd", strtotime($strtxtSakuseiYMD));
            }else{
              $strtxtSakuseiYMD = "";
            }
            $strcmSakuseisya =  $request->input('cmSakuseisya'.$cnt);
            $strcmKeitai =  $request->input('cmKeitai'.$cnt);
            $strtxtDaimoku =  $request->input('txtDaimoku'.$cnt);
            $strtxtGaiyou =  $request->input('txtGaiyou'.$cnt);
            $strcmDaibunnrui =  $request->input('cmDaibunnrui'.$cnt);
            $strcmSyoubunnrui =  $request->input('cmSyoubunnrui'.$cnt);
            $strcmHokan =  $request->input('cmHokan'.$cnt);
            $strtxtBikou =  $request->input('txtBikou'.$cnt);

            $strtxtBunsyoBangou =  $request->input('txtBunsyoBangou'.$cnt);
            if(trim($strtxtBunsyoBangou)==""){
              // 新規で文書番号を振る
              $strtxtBunsyoBangou=$this->FnBunsyoBangou_New($strcmDaibunnrui,$strcmSyoubunnrui);
            }else{
              // 大分類、小分類が変更になった場合番号を振り直さないといけない
              $intK =0;
              $intK =$this->FnDaiSyouChange($strID,$strcmDaibunnrui,$strcmSyoubunnrui);
              if($intK==1){
                // 変更有
                $strtxtBunsyoBangou=$this->FnBunsyoBangou_New($strcmDaibunnrui,$strcmSyoubunnrui);
              }
            }
            $strtxtHanban =  $request->input('txtHanban'.$cnt);
            $strtxtKey1 =  $request->input('txtKey1'.$cnt);
            $strtxtKey2 =  $request->input('txtKey2'.$cnt);
            $strtxtKey3 =  $request->input('txtKey3'.$cnt);
            $strtxtKey4 =  $request->input('txtKey4'.$cnt);
            $strtxtKey5 =  $request->input('txtKey5'.$cnt);
            $strtxtKey6 =  $request->input('txtKey6'.$cnt);
            $strtxtHokanYMD = $request->input('txtHokanYMD'.$cnt);
            if($strtxtHokanYMD!= ""){
              $strtxtHokanYMD = date("Ymd", strtotime($strtxtHokanYMD));
            }else{
              $strtxtHokanYMD = "";
            }
            $strcmNyuusyuKubun =  $request->input('cmNyuusyuKubun'.$cnt);
            $strcmZyuuyou =  $request->input('cmZyuuyou'.$cnt);
            
            if($strID == ""){
              $strID=$this->FnMaxID();
            }

            DB::beginTransaction();
            try {
                // 最初にデータを削除する
                $param = ['id' => $strID];

                $sql  = "DELETE FROM 文書管理データ";
                $sql .= " WHERE id = :id";
                $values = DB::delete($sql, $param);

                $writeRow = [];
                $writeRow['id'] = $strID;
                $writeRow['txtBunsyoBangou'] = trim($strtxtBunsyoBangou);
                $writeRow['cmTourokusya'] = trim($strcmTourokusya);
                $writeRow['txtSakuseiYMD'] = trim($strtxtSakuseiYMD);
                $writeRow['cmSakuseisya'] = trim($strcmSakuseisya);
                $writeRow['cmKeitai'] = trim($strcmKeitai);
                $writeRow['txtDaimoku'] = trim($strtxtDaimoku);
                $writeRow['txtGaiyou'] = trim($strtxtGaiyou);
                $writeRow['cmDaibunnrui'] = trim($strcmDaibunnrui);
                $writeRow['cmSyoubunnrui'] = trim($strcmSyoubunnrui);
                $writeRow['cmHokan'] = trim($strcmHokan);              
                $writeRow['txtHanban'] = trim($strtxtHanban);
                $writeRow['txtKey1'] = trim($strtxtKey1);
                $writeRow['txtKey2'] = trim($strtxtKey2);
                $writeRow['txtKey3'] = trim($strtxtKey3);
                $writeRow['txtKey4'] = trim($strtxtKey4);
                $writeRow['txtKey5'] = trim($strtxtKey5);
                $writeRow['txtKey6'] = trim($strtxtKey6);
                $writeRow['txtHokanYMD'] = trim($strtxtHokanYMD);
                $writeRow['cmNyuusyuKubun'] = trim($strcmNyuusyuKubun);
                $writeRow['cmZyuuyou'] = trim($strcmZyuuyou);
                $writeRow['txtBikou'] = trim($strtxtBikou);

                $sql = "INSERT INTO 文書管理データ(" .
                "id".
                ",文書番号".
                ",登録者区分コード".
                ",作成年月日".
                ",作成者コード".
                ",形態コード".
                ",題目".
                ",概要".
                ",大分類コード".
                ",小分類コード".
                ",保管先コード".
                ",版番号".
                ",キーワード1".
                ",キーワード2".
                ",キーワード3".
                ",キーワード4".
                ",キーワード5".
                ",キーワード6".
                ",保管期限".
                ",入手区分コード".
                ",重要区分コード".
                ",備考".
                ",更新日時".
                ")VALUES(".
                ":id".
                ", :txtBunsyoBangou".
                ", :cmTourokusya".
                ", :txtSakuseiYMD".
                ", :cmSakuseisya".
                ", :cmKeitai".
                ", :txtDaimoku".
                ", :txtGaiyou".
                ", :cmDaibunnrui".
                ", :cmSyoubunnrui".
                ", :cmHokan".
                ", :txtHanban".
                ", :txtKey1".
                ", :txtKey2".
                ", :txtKey3".
                ", :txtKey4".
                ", :txtKey5".
                ", :txtKey6".
                ", :txtHokanYMD".
                ", :cmNyuusyuKubun".
                ", :cmZyuuyou".
                ", :txtBikou".
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
        ,'文書番号' => ""
        ,'登録者区分コード' => ""
        ,'作成年月日' => ""
        ,'作成者コード' => ""
        ,'形態コード' => ""
        ,'題目' => ""
        ,'概要' => ""
        ,'大分類コード' => ""
        ,'小分類コード' => ""
        ,'保管先コード' => ""
        ,'版番号' => ""
        ,'キーワード1' => ""
        ,'キーワード2' => ""
        ,'キーワード3' => ""
        ,'キーワード4' => ""
        ,'キーワード5' => ""
        ,'キーワード6' => ""
        ,'保管期限' => ""
        ,'入手区分コード' => ""
        ,'重要区分コード' => ""
        ,'備考' => ""
      ];
      $itemvalues = MZCommon::FntoObject($item);
    }else{
      $strTitle = "検索";
      $itemvalues=$Data;
    }

    $dtSyain = $this->FnCmbSyainSet();
    $dtTourokusya = $this->FnCmbTourokusyaSet();
    $dtKeitai = $this->FnCmbKeitaiSet();
    $dtSyoubunnrui = $this->FnCmbSyoubunnruiSet();
    $dtDaibunnrui = $this->FnCmbDaibunnruiSet();
    $dtHokan = $this->FnCmbHokanSet();
    $dtNyuusyuKubun = $this->FnCmbNyuusyuKubunSet();
    $dtZyuuyou = $this->FnCmbZyuuyouSet();
    //※注意、TabIndex設定用、項目数が変わったらここも変更する。
    $intKSuu = 21;
    //-----------------------------------------------------
    $intMaxData = count($itemvalues);

    return view('project.mzwp0030', 
                compact('strTitle'
                ,'intMaxData'
                ,'itemvalues'
                , 'dtSyain'
                , 'dtTourokusya'
                , 'dtKeitai'
                , 'dtSyoubunnrui'
                , 'dtDaibunnrui'
                , 'dtHokan'
                , 'dtNyuusyuKubun'
                , 'dtZyuuyou'
                , 'intKSuu'
              ));
  }

  private function FnZyouken_array($request){
    // 条件変数設定
    $array_zyouken['題目'] = $request->input('txtDaimoku');
    $array_zyouken['文書番号'] = $request->input('txtBunsyoBangou');
    $array_zyouken['大分類'] = $request->input('cmDaibunnrui');
    $array_zyouken['小分類'] = $request->input('cmSyoubunnrui');
    $array_zyouken['保管期限'] = $request->input('txtHokanYMD');
    $array_zyouken['作成者'] = $request->input('cmSakuseisya');
    $array_zyouken['キーワード'] = $request->input('txtKey');
    $array_zyouken['重要区分'] = $request->input('cmZyuuyou');
    $array_zyouken['作成年'] = $request->input('cmYYYY');
  
    return $array_zyouken;
  }

  private function FnZyouken_array_New(){
    // 条件変数設定
    $array_zyouken['題目'] = "";
    $array_zyouken['文書番号'] = "";
    $array_zyouken['大分類'] = "";
    $array_zyouken['小分類'] = "";
    $array_zyouken['保管期限'] = "";
    $array_zyouken['作成者'] = "";
    $array_zyouken['重要区分'] = "";
    $array_zyouken['キーワード'] = "";
    $array_zyouken['作成年'] = "";

    return $array_zyouken;
  }

  private function FnPageSet_Kensaku($itemvalues,$intDataKubun){
    // 検索画面を表示する
    $strTitle = "検索";
    $dtSyain = $this->FnCmbSyainSet();
    $dtSyoubunnrui = $this->FnCmbSyoubunnruiSet();
    $dtDaibunnrui = $this->FnCmbDaibunnruiSet();
    $dtZyuuyou = $this->FnCmbZyuuyouSet();
    $dtYYYY = $this->FnYYYY_Set();

    return view('project.mzwp0031', 
                compact('strTitle'
                , 'intDataKubun'
                , 'itemvalues'
                , 'dtSyain'
                , 'dtSyoubunnrui'
                , 'dtDaibunnrui'
                , 'dtZyuuyou'
                , 'dtYYYY'
              ));
  }

  private function FnBunsyoBangou_New($strDai,$strSyou){
    // 文書番号連番(教育の文書番号はED-PL固定)
    $strDH = $this->FnDaiHead_Set($strDai);
    $strSH = $this->FnSyouHead_Set($strSyou);
    if($strDH==""||$strSH==""){
      // 片方でも空白なら作らない
      return "";
    }
    // info("a");
    $strBan = $strDH."-".$strSH;
    $strNewBangou = "";
    $blC=false;
    $intM = AlefCommon::null_i(AlefCommon::GetSystemData($strBan, "文書番号カウント"));

    do{
        If($intM >= 99999){
          $intM = 1;
        }Else{
          $intM += 1;
        }

        $strNewBangou = $strBan."-".sprintf('%05d', $intM);

        $strSQL = "(";
        $strSQL .= "SELECT 文書番号";
        $strSQL .= " FROM 文書管理データ";
        $strSQL .= " WHERE 文書番号='".$strNewBangou."'";
        $strSQL .= ")";
        $value = DB::select($strSQL);
        // info($strSQL);
        if(count($value)<=0){
          $blC=true;
        }
        // info($blC);
        // unset($value);//変数クリア
    }While ($blC = False);

    AlefCommon::PutSystemData($strBan, "文書番号カウント", strval($intM));

    return $strNewBangou;

  }

  private function FnDaiHead_Set($strDai){
    // 大分類の文書番号コードを取得
    if($strDai==""){
      // 空白
      return "";
    }
    $data ="";

    $strSQL = "(";
    $strSQL .= "SELECT 文書番号コード";
    $strSQL .= " FROM 文書管理_大分類マスタ";
    $strSQL .= " WHERE コード='".$strDai."'";
    $strSQL .= ")";
    $value = DB::select($strSQL);
    if(count($value)>0){
      $data = $value[0]->文書番号コード;
    }

    return $data;

  }

  private function FnSyouHead_Set($strSyou){
    // 小分類の文書番号コードを取得
    if($strSyou==""){
      // 空白
      return "";
    }
    $data ="";
    
    $strSQL = "(";
    $strSQL .= "SELECT 文書番号コード";
    $strSQL .= " FROM 文書管理_小分類マスタ";
    $strSQL .= " WHERE コード='".$strSyou."'";
    $strSQL .= ")";
    $value = DB::select($strSQL);
    // info($strSQL);
    if(count($value)>0){
      $data = $value[0]->文書番号コード;
    }

    return $data;

  }

  private function FnDaiSyouChange($strID,$strDai,$strSyou){
    // 小分類の文書番号コードを取得
    if(trim($strID)==""||trim($strDai)==""||trim($strSyou)==""){
      // 空白
      return 1;
    }
    $data ="";
    
    $strSQL = "(";
    $strSQL .= "SELECT 大分類コード,小分類コード";
    $strSQL .= " FROM 文書管理データ";
    $strSQL .= " WHERE id=".$strID."";
    $strSQL .= " AND 大分類コード='".$strDai."'";
    $strSQL .= " AND 小分類コード='".$strSyou."'";
    $strSQL .= ")";
    $value = DB::select($strSQL);
    if(count($value)>0){
      // idと小分類、大分類の組み合わせでデータが有れば変更の必要はない
      return 0;
    }else{
      // データが無ければ変更する必要がある。
      return 1;
    }
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
      private function FnCmbTourokusyaSet(){
        // 文書管理_登録者区分マスタをセットする
        $strSQL = "(";
        $strSQL .=" SELECT";
        $strSQL .=" コード as code";
        $strSQL .=",名称 as name";
        $strSQL .=" FROM  文書管理_登録者区分マスタ";
        $strSQL .=" )";
        $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
        $value = DB::select($strSQL);

        return $value;
    }
    private function FnCmbKeitaiSet(){
      // 文書管理_形態マスタをセットする
      $strSQL = "(";
      $strSQL .=" SELECT";
      $strSQL .=" コード as code";
      $strSQL .=",名称 as name";
      $strSQL .=" FROM  文書管理_形態マスタ";
      $strSQL .=" )";
      $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
      $value = DB::select($strSQL);

      return $value;
  }
      private function FnCmbSyoubunnruiSet(){
        //文書管理_小分類マスタをセットする
        $strSQL = "(";
        $strSQL .=" SELECT";
        $strSQL .=" コード as code";
        $strSQL .=",名称 as name";
        $strSQL .=" FROM  文書管理_小分類マスタ";
        $strSQL .=" )";
        $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
        $value = DB::select($strSQL);

        return $value;
    }
    private function FnCmbDaibunnruiSet(){
          //文書管理_大分類マスタをセットする
          $strSQL = "(";
          $strSQL .=" SELECT";
          $strSQL .=" コード as code";
          $strSQL .=",名称 as name";
          $strSQL .=" FROM  文書管理_大分類マスタ";
          $strSQL .=" )";
          $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
          $value = DB::select($strSQL);

          return $value;
      }
      private function FnCmbHokanSet(){
        //文書管理_保管先マスタをセットする
        $strSQL = "(";
        $strSQL .=" SELECT";
        $strSQL .=" コード as code";
        $strSQL .=",名称 as name";
        $strSQL .=" FROM  文書管理_保管先マスタ";
        $strSQL .=" )";
        $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
        $value = DB::select($strSQL);

        return $value;
    }
      private function FnCmbNyuusyuKubunSet(){
        // 文書管理_入手区分マスタをセットする
        $strSQL = "(";
        $strSQL .=" SELECT";
        $strSQL .=" コード as code";
        $strSQL .=",名称 as name";
        $strSQL .=" FROM  文書管理_入手区分マスタ";
        $strSQL .=" )";
        $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
        $value = DB::select($strSQL);

        return $value;
    }
    private function FnCmbZyuuyouSet(){
      //文書管理_重要区分マスタをセットする
      $strSQL = "(";
      $strSQL .=" SELECT";
      $strSQL .=" コード as code";
      $strSQL .=",名称 as name";
      $strSQL .=" FROM  文書管理_重要区分マスタ";
      $strSQL .=" )";
      $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
      $value = DB::select($strSQL);

      return $value;
  }
  private function FnYYYY_Set(){
    //実施年をセットする
    $YYYY = 2012;
    $End = MZCommon::FnNowDateTimeGet()->format("Y")+1;
    for($cnt = $YYYY ; $cnt <= $End ; $cnt++){
      $array['年'] = $cnt;
      $value[] = $array;
    }
    $Data = MZCommon::FntoObject($value);
    return $Data;
}

      private function FnMaxID(){
        //idの最大＋１を取得する。
        $maxNo = 0;
        $strSQL = "SELECT MAX(id) AS manNo FROM 文書管理データ";
        $value = DB::select($strSQL);
        if($value!= null){
            $maxNo = $value[0]->manNo;
        }
        $maxNo += 1;

        return $maxNo;
    }

}