<?php

namespace App\Http\Controllers\project;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // DB ファサードを use する
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Facades\AlefCommon;
use App\Facades\MZCommon;
use Carbon\Carbon;
class mzwp0020Controller extends Controller
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
      if($param_zyouken->教育項目!=""){
        $intCheck = 1;
        $array['Daibunnrui'] = $param_zyouken->教育項目;
      }
      if($param_zyouken->区分!=""){
        $intCheck = 1;
        $array['Syoubunnrui'] = $param_zyouken->区分;
      }
      if($param_zyouken->訓練番号!=""){
        $intCheck = 1;
        $array['KeikakuBangou'] = $param_zyouken->訓練番号;
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
      if($param_zyouken->受講者!=""){
        $intCheck = 1;
        $array['Taisyou1'] = $param_zyouken->受講者;
        $array['Taisyou2'] = $param_zyouken->受講者;
        $array['Taisyou3'] = $param_zyouken->受講者;
        $array['Taisyou4'] = $param_zyouken->受講者;
        $array['Taisyou5'] = $param_zyouken->受講者;
        $array['Taisyou6'] = $param_zyouken->受講者;
      }
      if($param_zyouken->実施年!=""){
        $intCheck = 1;
        $array['YYYY_S'] = $param_zyouken->実施年."0101";
        $array['YYYY_E'] = $param_zyouken->実施年."1231";
      }
      
      if($intCheck!=0){
        $param_select = $array;
      }else{
        $param_select = "";
      }
      
      $strSQL = "(";
      $strSQL .=" SELECT";
      $strSQL .=" ROW_NUMBER() OVER(ORDER BY 教育開始年月日,id) as 行番号";
      $strSQL .=",0 as DB区分";
      $strSQL .=",id";
      $strSQL .=",計画番号";
      $strSQL .=",(case when IsNull(計画年月日,'')<>'' and Len(rTrim(計画年月日))=8  then CONVERT(datetime, 計画年月日, 112) else Null end) as 計画年月日";
      $strSQL .=",登録者コード";
      $strSQL .=",教育担当コード";
      $strSQL .=",講師コード";
      $strSQL .=",題目";
      $strSQL .=",概要";
      $strSQL .=",大分類コード";
      $strSQL .=",小分類コード";
      $strSQL .=",(case when IsNull(教育開始年月日,'')<>'' and Len(rTrim(教育開始年月日))=8 then CONVERT(datetime, 教育開始年月日, 112) else Null end) as 教育開始年月日";
      $strSQL .=",(case when IsNull(教育終了年月日,'')<>'' and Len(rTrim(教育終了年月日))=8 then CONVERT(datetime, 教育終了年月日, 112) else Null end) as 教育終了年月日";
      $strSQL .=",版番号";
      $strSQL .=",対象者区分コード";
      $strSQL .=",キーワード1";
      $strSQL .=",キーワード2";
      $strSQL .=",キーワード3";
      $strSQL .=",キーワード4";
      $strSQL .=",キーワード5";
      $strSQL .=",キーワード6";
      $strSQL .=",対象者1コード";
      $strSQL .=",対象者2コード";
      $strSQL .=",対象者3コード";
      $strSQL .=",対象者4コード";
      $strSQL .=",対象者5コード";
      $strSQL .=",対象者6コード";
      $strSQL .=",重要区分コード";
      $strSQL .=",備考";
      $strSQL .=" FROM  教育データ";
      $strSQL .=" WHERE ";
      $strSQL .= " id > 0";
      if($param_zyouken->題目!=""){
        $strSQL .= " AND IsNull(題目,'') Like :Daimoku";
      }
      if($param_zyouken->教育項目!=""){
        $strSQL .= " AND IsNull(大分類コード,'') = :Daibunnrui";
      }
      if($param_zyouken->区分!=""){
        $strSQL .= " AND IsNull(小分類コード,'') = :Syoubunnrui";
      }
      if($param_zyouken->訓練番号!=""){
        $strSQL .= " AND IsNull(計画番号,'') = :KeikakuBangou";
      }
      if($param_zyouken->重要区分!=""){
        $strSQL .= " AND IsNull(重要区分コード,'') = :Zyuuyou";
      }
      if($param_zyouken->キーワード!=""){
        $strSQL .= " AND (IsNull(キーワード1,'') = :KeyZ1 or IsNull(キーワード2,'') = :KeyZ2 or IsNull(キーワード3,'') = :KeyZ3 or IsNull(キーワード4,'') = :KeyZ4 or IsNull(キーワード5,'') = :KeyZ5 or IsNull(キーワード6,'') = :KeyZ6)";
      }
      if($param_zyouken->受講者!=""){
        $strSQL .= " AND (IsNull(対象者1コード,'') = :Taisyou1 or IsNull(対象者2コード,'') = :Taisyou2 or IsNull(対象者3コード,'') = :Taisyou3 or IsNull(対象者4コード,'') = :Taisyou4 or IsNull(対象者5コード,'') = :Taisyou5 or IsNull(対象者6コード,'') = :Taisyou6)";
      }
      if($param_zyouken->実施年!=""){
        $strSQL .= " AND (IsNull(計画年月日,'') between :YYYY_S and :YYYY_E)";
      }
    
      $strSQL .=" )";
      $strSQL .=" ORDER BY 教育開始年月日,id";
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
            $strtxtKeikakuYMD = $request->input('txtKeikakuYMD'.$cnt);
            if($strtxtKeikakuYMD!= ""){
              $strtxtKeikakuYMD = date("Ymd", strtotime($strtxtKeikakuYMD));
            }else{
              $strtxtKeikakuYMD = "";
            }
            $strcmKyouikuTantou =  $request->input('cmKyouikuTantou'.$cnt);
            $strcmKousi =  $request->input('cmKousi'.$cnt);
            $strtxtDaimoku =  $request->input('txtDaimoku'.$cnt);
            $strtxtGaiyou =  $request->input('txtGaiyou'.$cnt);
            $strcmDaibunnrui =  $request->input('cmDaibunnrui'.$cnt);
            $strcmSyoubunnrui =  $request->input('cmSyoubunnrui'.$cnt);
            $strtxtKyouikuSYMD =  $request->input('txtKyouikuSYMD'.$cnt);
            if($strtxtKyouikuSYMD!= ""){
              $strtxtKyouikuSYMD = date("Ymd", strtotime($strtxtKyouikuSYMD));
            }else{
              $strtxtKyouikuSYMD = "";
            }
            $strtxtBikou =  $request->input('txtBikou'.$cnt);
            $strtxtKeikakuBangou =  $request->input('txtKeikakuBangou'.$cnt);
            if($strtxtKeikakuBangou==""){
              $strtxtKeikakuBangou=$this->FnKeikakuBangou_New();
            }
            $strtxtHanban =  $request->input('txtHanban'.$cnt);
            $strtxtKey1 =  $request->input('txtKey1'.$cnt);
            $strtxtKey2 =  $request->input('txtKey2'.$cnt);
            $strtxtKey3 =  $request->input('txtKey3'.$cnt);
            $strtxtKey4 =  $request->input('txtKey4'.$cnt);
            $strtxtKey5 =  $request->input('txtKey5'.$cnt);
            $strtxtKey6 =  $request->input('txtKey6'.$cnt);
            $strtxtKyouikuEYMD = $request->input('txtKyouikuEYMD'.$cnt);
            if($strtxtKyouikuEYMD!= ""){
              $strtxtKyouikuEYMD = date("Ymd", strtotime($strtxtKyouikuEYMD));
            }else{
              $strtxtKyouikuEYMD = "";
            }
            $strcmTaisyouKubun =  $request->input('cmTaisyouKubun'.$cnt);
            $strcmTaisyou1 =  $request->input('cmTaisyou1'.$cnt);
            $strcmTaisyou2 =  $request->input('cmTaisyou2'.$cnt);
            $strcmTaisyou3 =  $request->input('cmTaisyou3'.$cnt);
            $strcmTaisyou4 =  $request->input('cmTaisyou4'.$cnt);
            $strcmTaisyou5 =  $request->input('cmTaisyou5'.$cnt);
            $strcmTaisyou6 =  $request->input('cmTaisyou6'.$cnt);
            $strcmZyuuyou =  $request->input('cmZyuuyou'.$cnt);
            
            if($strID == ""){
              $strID=$this->FnMaxID();
            }

            DB::beginTransaction();
            try {
                // 最初にデータを削除する
                $param = ['id' => $strID];

                $sql  = "DELETE FROM 教育データ";
                $sql .= " WHERE id = :id";
                $values = DB::delete($sql, $param);

                $writeRow = [];
                $writeRow['id'] = $strID;
                $writeRow['txtKeikakuBangou'] = trim($strtxtKeikakuBangou);
                $writeRow['txtKeikakuYMD'] = trim($strtxtKeikakuYMD);
                $writeRow['cmTourokusya'] = trim($strcmTourokusya);
                $writeRow['cmKyouikuTantou'] = trim($strcmKyouikuTantou);
                $writeRow['cmKousi'] = trim($strcmKousi);
                $writeRow['txtDaimoku'] = trim($strtxtDaimoku);
                $writeRow['txtGaiyou'] = trim($strtxtGaiyou);
                $writeRow['cmDaibunnrui'] = trim($strcmDaibunnrui);
                $writeRow['cmSyoubunnrui'] = trim($strcmSyoubunnrui);
                $writeRow['txtKyouikuSYMD'] = trim($strtxtKyouikuSYMD);
                $writeRow['txtKyouikuEYMD'] = trim($strtxtKyouikuEYMD);
                $writeRow['txtHanban'] = trim($strtxtHanban);
                $writeRow['cmTaisyouKubun'] = trim($strcmTaisyouKubun);
                $writeRow['txtKey1'] = trim($strtxtKey1);
                $writeRow['txtKey2'] = trim($strtxtKey2);
                $writeRow['txtKey3'] = trim($strtxtKey3);
                $writeRow['txtKey4'] = trim($strtxtKey4);
                $writeRow['txtKey5'] = trim($strtxtKey5);
                $writeRow['txtKey6'] = trim($strtxtKey6);
                $writeRow['cmTaisyou1'] = trim($strcmTaisyou1);
                $writeRow['cmTaisyou2'] = trim($strcmTaisyou2);
                $writeRow['cmTaisyou3'] = trim($strcmTaisyou3);
                $writeRow['cmTaisyou4'] = trim($strcmTaisyou4);
                $writeRow['cmTaisyou5'] = trim($strcmTaisyou5);
                $writeRow['cmTaisyou6'] = trim($strcmTaisyou6);
                $writeRow['cmZyuuyou'] = trim($strcmZyuuyou);
                $writeRow['txtBikou'] = trim($strtxtBikou);

                $sql = "INSERT INTO 教育データ(" .
                "id".
                ",計画番号".
                ",計画年月日".
                ",登録者コード".
                ",教育担当コード".
                ",講師コード".
                ",題目".
                ",概要".
                ",大分類コード".
                ",小分類コード".
                ",教育開始年月日".
                ",教育終了年月日".
                ",版番号".
                ",対象者区分コード".
                ",キーワード1".
                ",キーワード2".
                ",キーワード3".
                ",キーワード4".
                ",キーワード5".
                ",キーワード6".
                ",対象者1コード".
                ",対象者2コード".
                ",対象者3コード".
                ",対象者4コード".
                ",対象者5コード".
                ",対象者6コード".
                ",重要区分コード".
                ",備考".
                ",更新日時".
                ")VALUES(".
                ":id".
                ", :txtKeikakuBangou".
                ", :txtKeikakuYMD".
                ", :cmTourokusya".
                ", :cmKyouikuTantou".
                ", :cmKousi".
                ", :txtDaimoku".
                ", :txtGaiyou".
                ", :cmDaibunnrui".
                ", :cmSyoubunnrui".
                ", :txtKyouikuSYMD".
                ", :txtKyouikuEYMD".
                ", :txtHanban".
                ", :cmTaisyouKubun".
                ", :txtKey1".
                ", :txtKey2".
                ", :txtKey3".
                ", :txtKey4".
                ", :txtKey5".
                ", :txtKey6".
                ", :cmTaisyou1".
                ", :cmTaisyou2".
                ", :cmTaisyou3".
                ", :cmTaisyou4".
                ", :cmTaisyou5".
                ", :cmTaisyou6".
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
        ,'計画番号' => ""
        ,'計画年月日' => ""
        ,'登録者コード' => ""
        ,'教育担当コード' => ""
        ,'講師コード' => ""
        ,'題目' => ""
        ,'概要' => ""
        ,'大分類コード' => ""
        ,'小分類コード' => ""
        ,'教育開始年月日' => ""
        ,'教育終了年月日' => ""
        ,'版番号' => ""
        ,'対象者区分コード' => ""
        ,'キーワード1' => ""
        ,'キーワード2' => ""
        ,'キーワード3' => ""
        ,'キーワード4' => ""
        ,'キーワード5' => ""
        ,'キーワード6' => ""
        ,'対象者1コード' => ""
        ,'対象者2コード' => ""
        ,'対象者3コード' => ""
        ,'対象者4コード' => ""
        ,'対象者5コード' => ""
        ,'対象者6コード' => ""
        ,'重要区分コード' => ""
        ,'備考' => ""
      ];
      $itemvalues = MZCommon::FntoObject($item);
    }else{
      $strTitle = "検索";
      $itemvalues=$Data;
    }

    $dtSyain = $this->FnCmbSyainSet();
    $dtTaisyouKubun = $this->FnCmbTaisyouKubunSet();
    $dtSyoubunnrui = $this->FnCmbSyoubunnruiSet();
    $dtDaibunnrui = $this->FnCmbDaibunnruiSet();
    $dtTourokusya = $this->FnCmbTourokusyaSet();
    $dtZyuuyou = $this->FnCmbZyuuyouSet();
    //※注意、TabIndex設定用、項目数が変わったらここも変更する。
    $intKSuu = 27;
    //-----------------------------------------------------
    $intMaxData = count($itemvalues);

    return view('project.mzwp0020', 
                compact('strTitle'
                ,'intMaxData'
                ,'itemvalues'
                , 'dtSyain'
                , 'dtTaisyouKubun'
                , 'dtSyoubunnrui'
                , 'dtDaibunnrui'
                , 'dtTourokusya'
                , 'dtZyuuyou'
                , 'intKSuu'
              ));
  }

  private function FnKeikakuBangou_New(){
    // 計画番号連番(教育の計画番号はED-PL固定)
    $strBan = "ED-PL";
    $strNewBangou = "";
    $blC=false;
    $intM = AlefCommon::null_i(AlefCommon::GetSystemData($strBan, "計画番号カウント"));

    do{
        If($intM >= 99999){
          $intM = 1;
        }Else{
          $intM += 1;
        }

        $strNewBangou = $strBan."-".sprintf('%05d', $intM);

        $strSQL = "(";
        $strSQL .= "SELECT 計画番号";
        $strSQL .= " FROM 教育データ";
        $strSQL .= " WHERE 計画番号='".$strNewBangou."'";
        $strSQL .= ")";
        $value = DB::select($strSQL);
        // info($strSQL);
        if(count($value)<=0){
          $blC=true;
        }
        // info($blC);
        // unset($value);//変数クリア
    }While ($blC = False);

    AlefCommon::PutSystemData($strBan, "計画番号カウント", strval($intM));

    return $strNewBangou;

  }

  private function FnZyouken_array($request){
    // 条件変数設定
    $array_zyouken['題目'] = $request->input('txtDaimoku');
    $array_zyouken['教育項目'] = $request->input('cmDaibunnrui');
    $array_zyouken['区分'] = $request->input('cmSyoubunnrui');
    $array_zyouken['訓練番号'] = $request->input('txtKeikakuBangou');
    $array_zyouken['重要区分'] = $request->input('cmZyuuyou');
    $array_zyouken['キーワード'] = $request->input('txtKey');
    $array_zyouken['受講者'] = $request->input('cmTaisyou');
    $array_zyouken['実施年'] = $request->input('cmYYYY');
  
    return $array_zyouken;
  }

  private function FnZyouken_array_New(){
    // 条件変数設定
    $array_zyouken['題目'] = "";
    $array_zyouken['教育項目'] = "";
    $array_zyouken['区分'] = "";
    $array_zyouken['訓練番号'] = "";
    $array_zyouken['重要区分'] = "";
    $array_zyouken['キーワード'] = "";
    $array_zyouken['受講者'] = "";
    $array_zyouken['実施年'] = "";

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
    
    // 教育項目	=大分類
    // 区分 =小分類
    // 受講者 =社員マスター

    return view('project.mzwp0021', 
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

    private function FnCmbTaisyouKubunSet(){
          // 教育_対象者区分マスタをセットする
          $strSQL = "(";
          $strSQL .=" SELECT";
          $strSQL .=" コード as code";
          $strSQL .=",名称 as name";
          $strSQL .=" FROM  教育_対象者区分マスタ";
          $strSQL .=" )";
          $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
          $value = DB::select($strSQL);

          return $value;
      }

      private function FnCmbSyoubunnruiSet(){
        //教育_小分類マスタをセットする
        $strSQL = "(";
        $strSQL .=" SELECT";
        $strSQL .=" コード as code";
        $strSQL .=",名称 as name";
        $strSQL .=" FROM  教育_小分類マスタ";
        $strSQL .=" )";
        $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
        $value = DB::select($strSQL);

        return $value;
    }

    private function FnCmbDaibunnruiSet(){
          //教育_大分類マスタをセットする
          $strSQL = "(";
          $strSQL .=" SELECT";
          $strSQL .=" コード as code";
          $strSQL .=",名称 as name";
          $strSQL .=" FROM  教育_大分類マスタ";
          $strSQL .=" )";
          $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
          $value = DB::select($strSQL);

          return $value;
      }

      private function FnCmbTourokusyaSet(){
        //教育_登録者マスタをセットする
        $strSQL = "(";
        $strSQL .=" SELECT";
        $strSQL .=" コード as code";
        $strSQL .=",名称 as name";
        $strSQL .=" FROM  教育_登録者マスタ";
        $strSQL .=" )";
        $strSQL .=" ORDER BY (case when IsNull(並び順,0)=0 then 99999 else 並び順 end)";
        $value = DB::select($strSQL);

        return $value;
    }

    private function FnCmbZyuuyouSet(){
      //教育_重要区分マスタをセットする
      $strSQL = "(";
      $strSQL .=" SELECT";
      $strSQL .=" コード as code";
      $strSQL .=",名称 as name";
      $strSQL .=" FROM  教育_重要区分マスタ";
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
        $strSQL = "SELECT MAX(id) AS manNo FROM 教育データ";
        $value = DB::select($strSQL);
        if($value!= null){
            $maxNo = $value[0]->manNo;
        }
        $maxNo += 1;

        return $maxNo;
    }

}