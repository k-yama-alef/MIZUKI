@extends('layout')
<!-- 以下(layout.blade.php)で表示 -->
@section('child')
{{-- 教育訓練 --}}
  <!-- <form id="mzwp0020" action="{{ route('mzwp0020_write') }}" method="post" onsubmit="return onclick_Check()"> -->
  <form id="mzwp0020" action="{{ route('mzwp0020_write') }}" method="post">
    <div class="Itiran-OoWaku"><!-- 幅固定用 -->
      <div id="recent-history">
        <a href="{{ route('home') }}">ホームへ</a>
        <div class="row py-2">
          <div class="col">
          <h2>教育訓練--{{ $strTitle }}</h2>
          </div>
        </div>
        <div class="Button_S Button_S_Waku">
          @csrf
          <button type="button" id="Tbutton1" onclick="Fnsubmit();" class="btn btn-primary" tabindex="-1">登録</button>
          @if($strTitle == '検索')
            <button type="button" id="Cbutton1" class="btn btn-primary" onclick="location.href='{{ route('mzwp0021') }}'" tabindex="-1">戻る</button>
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
              <th width=200>計画年月日 </th>
              <th width=200>教育担当</th>
              <th width=200>講師</th>
              <th width=200>題目</th>
              <th width=200>概要</th>
              <th width=200>大分類</th>
              <th width=200>小分類</th>
              <th>教育開始年月日</th>
              <th rowspan=2 width=150>備考</th>
            </tr>

            <tr>
              <th>計画番号</th>
              <th>版番号</th>
              <th>キーワード1</th>
              <th>キーワード2</th>
              <th>キーワード3</th>
              <th>キーワード4</th>
              <th>キーワード5</th>
              <th>キーワード6</th>
              <th>教育終了年月日</th>
            </tr>

            <tr>
              <th>対象者区分</th>
              <th>対象者1</th>
              <th>対象者2</th>
              <th>対象者3</th>
              <th>対象者4</th>
              <th>対象者5</th>
              <th>対象者6</th>
              <th> </th>
              <th> </th>
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
                          @if($value->登録者コード == $s_value->code  || Cookie::get('cmTourokusya') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t計画年月日  -->
                  <td><input name="txtKeikakuYMD{{ $value->行番号 }}" id="txtKeikakuYMD{{ $value->行番号 }}"  type="date" tabindex={{ 2+ ($value->行番号-1) * $intKSuu }} value={{ $value->計画年月日 }}></th>
                  <!-- t教育担当	-->
                  <td><select name="cmKyouikuTantou{{ $value->行番号 }}" id="cmKyouikuTantou{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 3+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtSyain as $s_value)
                          @if($value->教育担当コード == $s_value->code  ||  Cookie::get('cmKyouikuTantou') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t講師	-->
                  <td><select name="cmKousi{{ $value->行番号 }}" id="cmKousi{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 4+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtSyain as $s_value)
                          @if($value->講師コード == $s_value->code  ||  Cookie::get('cmKousi') == $s_value->code )
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
                  <!-- t教育開始年月日 -->
                  <td><input name="txtKyouikuSYMD{{ $value->行番号 }}" id="txtKyouikuSYMD{{ $value->行番号 }}" type="date" tabindex={{ 9+ ($value->行番号-1) * $intKSuu }} value={{ $value->教育開始年月日 }} required></td>
                  <!-- t備考 -->
                  <td rowspan=2><textarea class="textarea_2" name="txtBikou{{ $value->行番号 }}"  id="txtBikou{{ $value->行番号 }}" rows="2" maxlength="300" tabindex={{ 10+ ($value->行番号-1) * $intKSuu }}>{{ $value->備考 }}</textarea></td>
                </tr>

                <tr>
                  <!-- t計画番号	 -->
                  <td><input type="text" class="lock-color" name="txtKeikakuBangou{{ $value->行番号 }}" id="txtKeikakuBangou{{ $value->行番号 }}" inputmode="url" readonly="readonly" tabindex=-1 style="width:150px;" value={{ $value->計画番号 }} ></td>
                  <!-- t版番号 -->
                  <td><input type="text" name="txtHanban{{ $value->行番号 }}" id="txtHanban{{ $value->行番号 }}" maxlength="25" style="width:180px;" tabindex={{ 11+ ($value->行番号-1) * $intKSuu }} value={{ $value->版番号 }}></td>
                  <!-- tキーワード1 -->
                  <td><input type="text" name="txtKey1{{ $value->行番号 }}" id="txtKey1{{ $value->行番号 }}" maxlength="25" style="width:180px;" tabindex={{ 12+ ($value->行番号-1) * $intKSuu }} value={{ $value->キーワード1 }}></td>
                  <!-- tキーワード2 -->
                  <td><input type="text" name="txtKey2{{ $value->行番号 }}" id="txtKey2{{ $value->行番号 }}" maxlength="25" style="width:180px;" tabindex={{ 13+ ($value->行番号-1) * $intKSuu }} value={{ $value->キーワード2 }}></td>
                  <!-- tキーワード3 -->
                  <td><input type="text" name="txtKey3{{ $value->行番号 }}" id="txtKey3{{ $value->行番号 }}" maxlength="25" style="width:180px;" tabindex={{ 14+ ($value->行番号-1) * $intKSuu }} value={{ $value->キーワード3 }}></td>
                  <!-- tキーワード4 -->
                  <td><input type="text" name="txtKey4{{ $value->行番号 }}" id="txtKey4{{ $value->行番号 }}" maxlength="25" style="width:180px;" tabindex={{ 15+ ($value->行番号-1) * $intKSuu }} value={{ $value->キーワード4 }}></td>
                  <!-- tキーワード5 -->
                  <td><input type="text" name="txtKey5{{ $value->行番号 }}" id="txtKey5{{ $value->行番号 }}" maxlength="25" style="width:180px;"  tabindex={{ 16+ ($value->行番号-1) * $intKSuu }} value={{ $value->キーワード5 }}></td>
                  <!-- tキーワード6 -->
                  <td><input type="text" name="txtKey6{{ $value->行番号 }}" id="txtKey6{{ $value->行番号 }}" maxlength="25" style="width:180px;"  tabindex={{ 17+ ($value->行番号-1) * $intKSuu }} value={{ $value->キーワード6 }}></td>
                  <!-- t教育終了年月日 -->
                  <td><input name="txtKyouikuEYMD{{ $value->行番号 }}" id="txtKyouikuEYMD{{ $value->行番号 }}" type="date" tabindex={{ 18+ ($value->行番号-1) * $intKSuu }} value={{ $value->教育終了年月日 }} required></td>
                </tr>

                <tr>
                 <!-- t対象者区分 -->
                 <td><select name="cmTaisyouKubun{{ $value->行番号 }}" id="cmTaisyouKubun{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 19+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtTaisyouKubun as $s_value)
                          @if($value->対象者区分コード == $s_value->code  ||  Cookie::get('cmTaisyouKubun') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t対象者1 -->
                  <td><select name="cmTaisyou1{{ $value->行番号 }}" id="cmTaisyou1{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 20+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtSyain as $s_value)
                          @if($value->対象者1コード == $s_value->code  ||  Cookie::get('cmTaisyou1') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t対象者2 -->
                  <td><select name="cmTaisyou2{{ $value->行番号 }}" id="cmTaisyou2{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 21+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtSyain as $s_value)
                          @if($value->対象者2コード == $s_value->code  ||  Cookie::get('cmTaisyou2') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t対象者3 -->
                  <td><select name="cmTaisyou3{{ $value->行番号 }}" id="cmTaisyou3{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 22+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtSyain as $s_value)
                          @if($value->対象者3コード == $s_value->code  ||  Cookie::get('cmTaisyou3') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t対象者4 -->
                  <td><select name="cmTaisyou4{{ $value->行番号 }}" id="cmTaisyou4{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 23+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtSyain as $s_value)
                          @if($value->対象者4コード == $s_value->code  ||  Cookie::get('cmTaisyou4') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t対象者5 -->
                  <td><select name="cmTaisyou5{{ $value->行番号 }}" id="cmTaisyou5{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 24+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtSyain as $s_value)
                          @if($value->対象者5コード == $s_value->code  ||  Cookie::get('cmTaisyou5') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t対象者6 -->
                  <td><select name="cmTaisyou6{{ $value->行番号 }}" id="cmTaisyou6{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 25+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtSyain as $s_value)
                          @if($value->対象者6コード == $s_value->code  ||  Cookie::get('cmTaisyou6') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <td></td>
                  <td></td>
                  <!-- t重要区分 -->
                  <td><select name="cmZyuuyou{{ $value->行番号 }}" id="cmZyuuyou{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 26+ ($value->行番号-1) * $intKSuu }}>
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
        <font color='#ff0077'><h4>[[注記]]：</h4>
        <h5>対象者及び講師が「顧客」、「外注」、「その他」の場合は、備考欄にその名前を登録ください。</h5></font>
        <div class="Button_S Button_S_Waku" tabindex="-1">
          @csrf
          <button type="button" id="Tbutton2" onclick="Fnsubmit();" class="btn btn-primary" tabindex="-1">登録</button>
          @if($strTitle == '検索')
            <button type="button" id="Cbutton2" class="btn btn-primary" onclick="location.href='{{ route('mzwp0021') }}'" tabindex="-1">戻る</button>
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
        var id = "txtKyouikuSYMD"+step;
        data=document.getElementById(id).value;
        if (data==""){
          alert("教育開始年月日を入力してください。");
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
        $('#mzwp0020').submit();
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
  // $('#mzwp0020').submit(function(){
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
        var id = "txtKeikakuYMD"+intGyou;
        document.getElementById(id).value=today;
        var id = "cmKyouikuTantou"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmKousi"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtDaimoku"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtGaiyou"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmDaibunnrui"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmSyoubunnrui"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtKyouikuSYMD"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtBikou"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtKeikakuBangou"+intGyou;
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
        var id = "txtKyouikuEYMD"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmTaisyouKubun"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmTaisyou1"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmTaisyou2"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmTaisyou3"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmTaisyou4"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmTaisyou5"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmTaisyou6"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmZyuuyou"+intGyou;
        document.getElementById(id).value= "";

    }
}

</script>