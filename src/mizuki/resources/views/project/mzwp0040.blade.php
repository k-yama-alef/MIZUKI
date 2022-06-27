@extends('layout')
<!-- 以下(layout.blade.php)で表示 -->
@section('child')
{{-- 計測器・治工具管理 --}}
  <!-- <form id="mzwp0040" action="{{ route('mzwp0040_write') }}" method="post" onsubmit="return onclick_Check()"> -->
  <form id="mzwp0040" action="{{ route('mzwp0040_write') }}" method="post">
    <div class="Itiran-OoWaku"><!-- 幅固定用 -->
      <div id="recent-history">
        <a href="{{ route('home') }}">ホームへ</a>
        <div class="row py-2">
          <div class="col">
          <h2>計測器・治工具管理--{{ $strTitle }}</h2>
          </div>
        </div>
        <div class="Button_S Button_S_Waku">
          @csrf
          <button type="button" id="Tbutton1" onclick="Fnsubmit();" class="btn btn-primary" tabindex="-1">登録</button>
          @if($strTitle == '検索')
            <button type="button" id="Cbutton1" class="btn btn-primary" onclick="location.href='{{ route('mzwp0041') }}'" tabindex="-1">戻る</button>
          @else
            <button type="button" id="Cbutton1" class="btn btn-primary" onclick="clearForm()" tabindex="-1">取消</button>
          @endif
        </div>
      </div>

      <!-- @if (session('error')) 
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif -->
      
      <body>
        <section class="page">
        <!-- <input type="hidden" name="txtonclick_Check" id="txtonclick_Check" value="">submitが不要の場合1を入れる -->
        <input type="hidden" name="txtMaxData" id="txtMaxData" value={{ $intMaxData }}><!-- 最大件数を持っておく -->
        <link rel="stylesheet" href="{{asset('css/mzcommon.css?'.time())}}">
        <div class="cssOutline2">
        <link rel="stylesheet" type="text/css" href="mzcommon.css" media="print" />
        <table align="left">     
          <!-- ***ヘッダー部分指定*** -->
          <thead>
            <tr>
              <th rowspan=2 width=50>No</th>
              <th rowspan=1 width=190>管理種別</th>
              <th width=200>名称・型式</th>
              <th rowspan=2 width=200>測定範囲</th>
              <th rowspan=2 width=200>メーカ名</th>
              <th rowspan=2 width=180>登録日</th>
              <th width=200>借用者</th>
              <th rowspan=2 width=200>校正区分</th>
              <th rowspan=2 width=200>用途区分</th>
              <th width=180>検定実施日</th>
              <th rowspan=2 width=150>備考</th>
            </tr>

            <tr>
              <th>管理番号</th>
              <th>製造番号</th>
              <th>保管場所</th>
              <th>次回検定日</th>
            </tr>
          </thead>
          <!----------------------->
          @foreach ($itemvalues as $value)
            <tbody>
              <tr>
                <!-- IDがあれば入れる -->
                <input type="hidden" name="txtID{{ $value->行番号 }}" id="txtID{{ $value->行番号 }}" value={{ $value->id }}>
                  <td rowspan=2 align="center">{{ $value->行番号 }}</td>
                  <!-- t管理種別 -->
                  <td rowspan=1><select name="cmKanrisyubetu{{ $value->行番号 }}" id="cmKanrisyubetu{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 1+ ($value->行番号-1) * $intKSuu }} required  required @if($value->行番号=='1') autofocus  @endif>
                      <option value="">
                      @foreach($dtKanrisyubetu as $s_value)
                          @if($value->管理種別コード == $s_value->code  || Cookie::get('cmKanrisyubetu') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t名称・型式 -->
                  <td><input type="text" name="txtMeisyouKatasiki{{ $value->行番号 }}" id="txtMeisyouKatasiki{{ $value->行番号 }}" maxlength="30" tabindex={{ 2+ ($value->行番号-1) * $intKSuu }}  value={{ $value->名称型式 }}></td>
                  <!-- t測定範囲	 -->
                  <td rowspan=2><input type="text" name="txtSokuteiHanni{{ $value->行番号 }}" id="txtSokuteiHanni{{ $value->行番号 }}" maxlength="25" tabindex={{ 4+ ($value->行番号-1) * $intKSuu }}  value={{ $value->測定範囲 }}></td>
                  <!-- tメーカ名	-->
                  <td rowspan=2><select name="cmMaker{{ $value->行番号 }}" id="cmMaker{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 5+ ($value->行番号-1) * $intKSuu }} >
                      <option value="">
                      @foreach($dtMaker as $s_value)
                          @if($value->メーカーコード == $s_value->code  ||  Cookie::get('cmMaker') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t登録日  -->
                  <td rowspan=2><input name="txtTourokuYMD{{ $value->行番号 }}" id="txtTourokuYMD{{ $value->行番号 }}"  type="date" tabindex={{ 6+ ($value->行番号-1) * $intKSuu }}  value={{ $value->登録日 }}></th>
                  <!-- t借用者	-->
                  <td><select name="cmSyakuyou{{ $value->行番号 }}" id="cmSyakuyou{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 7+ ($value->行番号-1) * $intKSuu }} >
                      <option value="">
                      @foreach($dtSyakuyou as $s_value)
                          @if($value->借用者コード == $s_value->code  ||  Cookie::get('cmSyakuyou') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t校正区分	-->
                  <td rowspan=2><select name="cmKouseiKubun{{ $value->行番号 }}" id="cmKouseiKubun{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 9+ ($value->行番号-1) * $intKSuu }} >
                      <option value="">
                      @foreach($dtKouseiKubun as $s_value)
                          @if($value->校正区分コード == $s_value->code  ||  Cookie::get('cmKouseiKubun') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t用途区分 -->
                  <td rowspan=2><select name="cmYoutoKubun{{ $value->行番号 }}" id="cmYoutoKubun{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 10+ ($value->行番号-1) * $intKSuu }} >
                      <option value="">
                      @foreach($dtYoutoKubun as $s_value)
                          @if($value->用途区分コード == $s_value->code  ||  Cookie::get('cmYoutoKubun') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t検定実施日 -->
                  <td><input name="txtKenteiZissibi{{ $value->行番号 }}" id="txtKenteiZissibi{{ $value->行番号 }}" type="date" tabindex={{ 11+ ($value->行番号-1) * $intKSuu }}  value={{ $value->検定実施日 }} required></td>
                   <!-- t備考 -->
                   <td rowspan=2><textarea class="textarea_2" name="txtBikou{{ $value->行番号 }}"  id="txtBikou{{ $value->行番号 }}" rows="2" maxlength="300" tabindex={{ 13+ ($value->行番号-1) * $intKSuu }} >{{ $value->備考 }}</textarea></td>
                </tr>

                <tr>
                  <!-- t管理番号	 -->
                  <td><input type="text" name="txtKanriBangou{{ $value->行番号 }}" class="lock-color" id="txtKanriBangou{{ $value->行番号 }}" inputmode="url" readonly="readonly" style="width:190px;" tabindex=-1 value={{ $value->管理番号 }} ></td>
                  <!-- t製造番号	 -->
                  <td><input type="text" name="txtSeizouBangou{{ $value->行番号 }}" id="txtSeizouBangou{{ $value->行番号 }}" maxlength="25" tabindex={{ 3+ ($value->行番号-1) * $intKSuu }}  value={{ $value->製造番号 }}></td>
                  <!-- t保管場所 -->
                  <td><select name="cmHokan{{ $value->行番号 }}" id="cmHokan{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 8+ ($value->行番号-1) * $intKSuu }} >
                      <option value="">
                      @foreach($dtHokan as $s_value)
                          @if($value->保管場所コード == $s_value->code  ||  Cookie::get('cmHokan') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t次回検定日 -->
                  <td><input name="txtZikaiKentei{{ $value->行番号 }}" id="txtZikaiKentei{{ $value->行番号 }}" type="date" tabindex={{ 12+ ($value->行番号-1) * $intKSuu }}  value={{ $value->次回検定日 }} required></td>
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
            <button type="button" id="Cbutton2" class="btn btn-primary" onclick="location.href='{{ route('mzwp0041') }}'" tabindex="-1">戻る</button>
          @else
            <button type="button" id="Cbutton2" class="btn btn-primary" onclick="clearForm()" tabindex="-1">取消</button>
          @endif
        </div>
        </div>
        </section>
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
        var id = "cmKanrisyubetu"+step;
        data=document.getElementById(id).value;
        // alert(data);
        if (data==""){
          alert("管理種別を入力してください。");
          document.getElementById(id).focus();
          //ボタンロック解除
          Fnbutton_disabled(false);
          return false;
        }
        var id = "cmSyakuyou"+step;
        data=document.getElementById(id).value;
        // alert(data);
        if (data==""){
          alert("借用者を入力してください。");
          document.getElementById(id).focus();
          //ボタンロック解除
          Fnbutton_disabled(false);
          return false;
        }
        var id = "txtKenteiZissibi"+step;
        data=document.getElementById(id).value;
        if (data==""){
          alert("検定実施日を入力してください。");
          document.getElementById(id).focus();
          //ボタンロック解除
          Fnbutton_disabled(false);
          return false;
        }
        var id = "txtZikaiKentei"+step;
        data=document.getElementById(id).value;
        if (data==""){
          alert("次回検定日を入力してください。");
          document.getElementById(id).focus();
          //ボタンロック解除
          Fnbutton_disabled(false);
          return false;
        }
      }
      dispLoading();
        $('#mzwp0040').submit();
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
  // $('#mzwp0040').submit(function(){
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

        var id = "cmKanrisyubetu"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtMeisyouKatasiki"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtSokuteiHanni"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmMaker"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtTourokuYMD"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmSyakuyou"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmKouseiKubun"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmYoutoKubun"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtZikaiKentei"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtSeizouBangou"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmHokan"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtKenteiZissibi"+intGyou;
        document.getElementById(id).value= "";

    }
}

</script>