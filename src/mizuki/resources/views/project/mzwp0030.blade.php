@extends('layout')
<!-- 以下(layout.blade.php)で表示 -->
@section('child')
{{-- 文書管理 --}}
  <!-- <form id="mzwp0030" action="{{ route('mzwp0030_write') }}" method="post" onsubmit="return onclick_Check()"> -->
  <form id="mzwp0030" action="{{ route('mzwp0030_write') }}" method="post">
    <div class="Itiran-OoWaku"><!-- 幅固定用 -->
      <div id="recent-history">
        <a href="{{ route('home') }}">ホームへ</a>
        <div class="row py-2">
          <div class="col">
          <h2>文書管理--{{ $strTitle }}</h2>
          </div>
        </div>
        <div class="Button_S Button_S_Waku">
          @csrf
          <button type="button" id="Tbutton1" onclick="Fnsubmit();" class="btn btn-primary" tabindex="-1">登録</button>
          @if($strTitle == '検索')
            <button type="button" id="Cbutton1" class="btn btn-primary" onclick="location.href='{{ route('mzwp0031') }}'" tabindex="-1">戻る</button>
          @else
            <button type="button" id="Cbutton1" class="btn btn-primary" onclick="clearForm()" tabindex="-1">取消</button>
          @endif
        </div>
      </div>

      <!-- @if (session('error')) 
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif -->
      
      <body>
        <!-- <input type="hidden" name="txtonclick_Check" id="txtonclick_Check" value="">submitが不要の場合1を入れる -->
        <input type="hidden" name="txtMaxData" id="txtMaxData" value={{ $intMaxData }}><!-- 最大件数を持っておく -->
        <link rel="stylesheet" href="{{asset('css/mzcommon.css?'.time())}}">
        <div class="cssOutline">
        <link rel="stylesheet" type="text/css" href="mzcommon.css" media="print" />
        <table align="left">    
          <!-- ***ヘッダー部分指定*** -->
          <thead>
            <tr>
              <th width=100>登録者</th>
              <th width=180>作成年月日 </th>
              <th width=200>作成者</th>
              <th width=200>形態</th>
              <th width=200>題目</th>
              <th width=200>概要</th>
              <th width=200>大分類</th>
              <th width=200>小分類</th>
              <th width=170>保管先</th>
              <th rowspan=2 width=150>備考</th>
            </tr>

            <tr>
              <th>文書番号（通常空白)</th>
              <th>版番号</th>
              <th>キーワード1</th>
              <th>キーワード2</th>
              <th>キーワード3</th>
              <th>キーワード4</th>
              <th>キーワード5</th>
              <th>キーワード6</th>
              <th>保管期限</th>
            </tr>

            <tr>
              <th>入手区分</th>
              <th colspan=8> </th>
              <th>重要区分</th>
            </tr>
          </thead>
          <!----------------------->
          @foreach ($itemvalues as $value)
            <tbody>
              <tr>
                <!-- IDがあれば入れる -->
                <input type="hidden" name="txtID{{ $value->行番号 }}" id="txtID{{ $value->行番号 }}" value={{ $value->id }}>
                  <!-- t登録者 -->
                  <td><select name="cmTourokusya{{ $value->行番号 }}" id="cmTourokusya{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 1+ ($value->行番号-1) * $intKSuu }} required  required @if($value->行番号=='1') autofocus  @endif>
                      <option value="">
                      @foreach($dtTourokusya as $s_value)
                          @if($value->登録者区分コード == $s_value->code  || Cookie::get('cmTourokusya') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t作成年月日  -->
                  <td><input name="txtSakuseiYMD{{ $value->行番号 }}" id="txtSakuseiYMD{{ $value->行番号 }}"  type="date" tabindex={{ 2+ ($value->行番号-1) * $intKSuu }} value={{ $value->作成年月日 }}></th>
                  <!-- t作成者	-->
                  <td><select name="cmSakuseisya{{ $value->行番号 }}" id="cmSakuseisya{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 3+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtSyain as $s_value)
                          @if($value->作成者コード == $s_value->code  ||  Cookie::get('cmSakuseisya') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t形態	-->
                  <td><select name="cmKeitai{{ $value->行番号 }}" id="cmKeitai{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 4+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtKeitai as $s_value)
                          @if($value->形態コード == $s_value->code  ||  Cookie::get('cmKeitai') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t題目 -->
                  <td><input type="text" name="txtDaimoku{{ $value->行番号 }}" id="txtDaimoku{{ $value->行番号 }}" maxlength="50" tabindex={{ 5+ ($value->行番号-1) * $intKSuu }} value={{ $value->題目 }}></td>
                    <!-- t概要 -->
                    <td><textarea name="txtGaiyou{{ $value->行番号 }}"  id="txtGaiyou{{ $value->行番号 }}" maxlength="300" tabindex={{ 10+ ($value->行番号-1) * $intKSuu }}>{{ $value->概要 }}</textarea></td>
                  <!-- t大分類	-->
                  <td><select name="cmDaibunnrui{{ $value->行番号 }}" id="cmDaibunnrui{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 7+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtDaibunnrui as $s_value)
                          @if($value->大分類コード == $s_value->code  ||  Cookie::get('cmDaibunnrui') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t小分類 -->
                  <td><select name="cmSyoubunnrui{{ $value->行番号 }}" id="cmSyoubunnrui{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 8+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtSyoubunnrui as $s_value)
                          @if($value->小分類コード == $s_value->code  ||  Cookie::get('cmSyoubunnrui') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t保管先 -->
                  <td><select name="cmHokan{{ $value->行番号 }}" id="cmHokan{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 9+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtHokan as $s_value)
                          @if($value->保管先コード == $s_value->code  ||  Cookie::get('cmHokan') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                   <!-- t備考 -->
                   <td rowspan=2><textarea class="textarea_2" name="txtBikou{{ $value->行番号 }}"  id="txtBikou{{ $value->行番号 }}" rows="2" maxlength="300" tabindex={{ 10+ ($value->行番号-1) * $intKSuu }}>{{ $value->備考 }}</textarea></td>
                </tr>

                <tr>
                  <!-- t文書番号	 -->
                  <td><input type="text" name="txtBunsyoBangou{{ $value->行番号 }}" class="lock-color" id="txtBunsyoBangou{{ $value->行番号 }}" inputmode="url" readonly="readonly" style="width:150px;" tabindex=-1 value={{ $value->文書番号 }} ></td>
                  <!-- t版番号 -->
                  <td><input type="text" name="txtHanban{{ $value->行番号 }}" id="txtHanban{{ $value->行番号 }}" maxlength="25" style="width:150px;" tabindex={{ 11+ ($value->行番号-1) * $intKSuu }} value={{ $value->版番号 }}></td>

                  <!-- tキーワード1 -->
                  <td><input type="text" name="txtKey1{{ $value->行番号 }}" id="txtKey1{{ $value->行番号 }}" maxlength="25" style="width:200px;" tabindex={{ 12+ ($value->行番号-1) * $intKSuu }} value={{ $value->キーワード1 }}></td>
                  <!-- tキーワード2 -->
                  <td><input type="text" name="txtKey2{{ $value->行番号 }}" id="txtKey2{{ $value->行番号 }}" maxlength="25" style="width:200px;" tabindex={{ 13+ ($value->行番号-1) * $intKSuu }} value={{ $value->キーワード2 }}></td>
                  <!-- tキーワード3 -->
                  <td><input type="text" name="txtKey3{{ $value->行番号 }}" id="txtKey3{{ $value->行番号 }}" maxlength="25" style="width:200px;" tabindex={{ 14+ ($value->行番号-1) * $intKSuu }} value={{ $value->キーワード3 }}></td>
                  <!-- tキーワード4 -->
                  <td><input type="text" name="txtKey4{{ $value->行番号 }}" id="txtKey4{{ $value->行番号 }}" maxlength="25" style="width:200px;" tabindex={{ 15+ ($value->行番号-1) * $intKSuu }} value={{ $value->キーワード4 }}></td>
                  <!-- tキーワード5 -->
                  <td><input type="text" name="txtKey5{{ $value->行番号 }}" id="txtKey5{{ $value->行番号 }}" maxlength="25" style="width:200px;" tabindex={{ 16+ ($value->行番号-1) * $intKSuu }} value={{ $value->キーワード5 }}></td>
                  <!-- tキーワード6 -->
                  <td><input type="text" name="txtKey6{{ $value->行番号 }}" id="txtKey6{{ $value->行番号 }}" maxlength="25" style="width:200px;" tabindex={{ 17+ ($value->行番号-1) * $intKSuu }} value={{ $value->キーワード6 }}></td>
                  <!-- t保管期限 -->
                  <td><input name="txtHokanYMD{{ $value->行番号 }}" id="txtHokanYMD{{ $value->行番号 }}" type="date" tabindex={{ 18+ ($value->行番号-1) * $intKSuu }} value={{ $value->保管期限 }} required></td>
                </tr>

                <tr>
                 <!-- t入手区分 -->
                 <td><select name="cmNyuusyuKubun{{ $value->行番号 }}" id="cmNyuusyuKubun{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 19+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtNyuusyuKubun as $s_value)
                          @if($value->入手区分コード == $s_value->code  ||  Cookie::get('cmNyuusyuKubun') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                   <td colspan=8></td>
                  <!-- t重要区分 -->
                  <td><select name="cmZyuuyou{{ $value->行番号 }}" id="cmZyuuyou{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 20+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtZyuuyou as $s_value)
                          @if($value->重要区分コード == $s_value->code  ||  Cookie::get('cmZyuuyou') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                </tr>
              </tbody>
            @endforeach

        </table>
      </div>
        </div>
        <br clear="left"><!-- テーブルの下に回り込む -->
        <div class="Button_S Button_S_Waku" tabindex="-1">
          @csrf
          <button type="button" id="Tbutton2" onclick="Fnsubmit();" class="btn btn-primary" tabindex="-1">登録</button>
          @if($strTitle == '検索')
            <button type="button" id="Cbutton2" class="btn btn-primary" onclick="location.href='{{ route('mzwp0031') }}'" tabindex="-1">戻る</button>
          @else
            <button type="button" id="Cbutton2" class="btn btn-primary" onclick="clearForm()" tabindex="-1">取消</button>
          @endif
        </div>
        </div>
      </body>
    </div><!-- 幅固定用 -->
  </form>
@endsection

<!-- <script src="jquery-3.6.0.min.js"></script>
<script type="text/javascript">
  $(window).load(function() {
    $('input,select').on('keypress', function(ev){
      var $me = $(this);
      var $list = $('input:enabled:not([readonly]),select:enabled,textarea:enabled');
      if (ev.keyCode == 13) {
        $list.each(function(index){
          if ($(this).is($me)) {
            $list.eq(index+1).focus();
            ev.preventDefault();
          }
        });
      }
    });
  });
</script> -->
<!-- <script src="{{asset('js/commonTools.js?'.time())}}"></script> -->
<script>
  function Fnsubmit(){
    if (confirm("登録処理を行います。よろしいですか？")) {
      //ボタンロック
      Fnbutton_disabled(true);
      var id = "txtMaxData";
      let intMax=document.getElementById(id).value;
      for (let step = 1; step <= intMax; step++) {
        var id = "cmTourokusya"+step;
        data=document.getElementById(id).value;
        // alert(data);
        if (data==""){
          alert("登録者を入力してください。");
          document.getElementById(id).focus();
          //ボタンロック解除
          Fnbutton_disabled(false);
          return false;
        }
        var id = "txtSakuseiYMD"+step;
        data=document.getElementById(id).value;
        if (data==""){
          alert("作成年月日を入力してください。");
          document.getElementById(id).focus();
          //ボタンロック解除
          Fnbutton_disabled(false);
          return false;
        }
        var id = "txtDaimoku"+step;
        data=document.getElementById(id).value;
        if (data==""){
          alert("題目を入力してください。");
          document.getElementById(id).focus();
          //ボタンロック解除
          Fnbutton_disabled(false);
          return false;
        }
        var id = "txtGaiyou"+step;
        data=document.getElementById(id).value;
        if (data==""){
          alert("概要を入力してください。");
          document.getElementById(id).focus();
          //ボタンロック解除
          Fnbutton_disabled(false);
          return false;
        }
      }
      dispLoading();
        $('#mzwp0030').submit();
    }
  }
    //commonToolsからコピー（jsの読み込みが上手くいかず）
    function dispLoading(sec = 10){   
    var waitTime = sec * 1000;
    var dispMsg = "<div class='loadingMsg'>Now Loading...</div>";
      var len = Number($("#loading").length);
    if( len == 0){
          $("body").append("<div id='loading'>" + dispMsg + "</div>");
    }
      setTimeout("removeLoading()", waitTime);
    }
    function removeLoading(){
      $("#loading").remove();
    }
    //------------------------------------------------
  // $('#mzwp0030').submit(function(){
  //     dispLoading();
  // }
  // );

  //Enterキーでフォーカス移動
  $("input").keydown(function(e) {
    var n = $(".e_focus").length;
    if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
        var Index = $('.e_focus').index(this);
        var nextIndex = $('.e_focus').index(this) + 1;
        if (nextIndex < n) {
            $('.e_focus')[nextIndex].focus();   //次の要素へフォーカスを移動
        } else {
            $('.e_focus')[Index].blur();        //最後の要素ではフォーカスを外す
        }
        return false;
    } else {
        return true;
    }
  });

  //登録等のボタンのロック、解除
  function Fnbutton_disabled(str){
    $('#Tbutton1').prop('disabled',str);
    $('#Cbutton1').prop('disabled',str);
    $('#Tbutton2').prop('disabled',str);
    $('#Cbutton2').prop('disabled',str);
}

function FnisHanEisu_Check(str){
  str = (str==null)?"":str;
  if(str.match(/^[A-Za-z0-9]*$/)){
    return true;
  }else{
    return false;
  }
}

function FnisHanSu_Check(str){
  str = (str==null)?"":str;
  if(str.match(/^[z0-9]*$/)){
    return true;
  }else{
    return false;
  }
}

function clearForm(){
    // 画面をクリアする
    // 隠しテキストに1を入れる
    // document.getElementById('txtonclick_Check').value= '1';
    if (confirm("取消処理を行います。よろしいですか？")) {
        var intGyou=1;// ここに走るのは新規のみ
        var dDate = new Date();
        var today = dDate.getFullYear()  + "-" +
				("00" + (dDate.getMonth() + 1)).slice(-2)  +  "-" +
				("00" + (dDate.getDate())).slice(-2); 

        var id = "cmTourokusya"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtSakuseiYMD"+intGyou;
        document.getElementById(id).value=today;
        var id = "cmSakuseisya"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmKeitai"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtDaimoku"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtGaiyou"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmDaibunnrui"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmSyoubunnrui"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmHokan"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtBikou"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtBunsyoBangou"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtHanban"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtKey1"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtKey2"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtKey3"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtKey4"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtKey5"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtKey6"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtHokanYMD"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmNyuusyuKubun"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmZyuuyou"+intGyou;
        document.getElementById(id).value= "";

    }
}

</script>