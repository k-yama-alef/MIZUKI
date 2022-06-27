@extends('layout')
<!-- 以下(layout.blade.php)で表示 -->
@section('child')
{{-- 品質管理 --}}
  <!-- <form id="mzwp0010" action="{{ route('mzwp0010_write') }}" method="post" onsubmit="return onclick_Check()"> -->         
  <form id="mzwp0010" action="{{ route('mzwp0010_write') }}" method="post">
    <div class="Itiran-OoWaku"><!-- 幅固定用 -->
      <div id="recent-history">
        <a href="{{ route('home') }}">ホームへ</a>
        <div class="row py-2">
          <div class="col">
          <h2>品質管理--{{ $strTitle }}</h2>
          </div>
        </div>
        <div class="Button_S Button_S_Waku" tabindex="-1">
          @csrf
          <button type="button" id="Tbutton1" onclick="Fnsubmit();" class="btn btn-primary" tabindex="-1">登録</button>
          @if($strTitle == '検索')
            <button type="button" id="Cbutton1" class="btn btn-primary" onclick="location.href='{{ route('mzwp0011') }}'" tabindex="-1">戻る</button>
          @else
            <button type="button" id="Cbutton1" class="btn btn-primary" onclick="clearForm()" tabindex="-1">取消</button>
          @endif
        </div>
      </div>
<!-- 
      @if (session('error')) 
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif -->
      
      <body>
        <link rel="stylesheet" href="{{asset('css/mzcommon.css?'.time())}}">
        <!-- <input type="hidden" name="txtonclick_Check" id="txtonclick_Check" value="">submitが不要の場合1を入れる -->
        <input type="hidden" name="txtMaxData" id="txtMaxData" value={{ $intMaxData }}><!-- 最大件数を持っておく -->
        <div class="cssOutline">
        <link rel="stylesheet" type="text/css" href="mzcommon.css" media="print" />
        <!-- <section class="page"> -->
        <table align="left">       
          <!-- ***ヘッダー部分指定*** -->
          <thead>
            <tr>
              <th width=200>発生年月日 </th>
              <th width=200>顧客</th>
              <th width=200>検査数</th>
              <th width=200>不適合内容</th>
              <th width=200>重大レベル</th>
              <th width=200>物的要因</th>
              <th width=200>発見</th>
              <th rowspan=2 width=150>備考</th>
            </tr>

            <tr>
              <th><font color="red">不適合製品タグNo</font></th>
              <th>不適合区分</th>
              <th>不適合数</th>
              <th>原因</th>
              <th>処置</th>
              <th>人的要因</th>
              <th>発生－外注</th>
            </tr>

            <tr>
              <th>品名</th>
              <th>品番</th>
              <th>S/No</th>
              <th>是正処置</th>
              <th>是正処置No I-TAGNo</th>
              <th>特採申請No</th>
              <th>発生－社内</th>
              <th>是正処置実施確認日<br></th>
            </tr>
          </thead>
          <!----------------------->
          @foreach ($itemvalues as $value)
            <tbody>
              <tr>
                <!-- IDがあれば入れる -->
                <input type="hidden" name="txtID{{ $value->行番号 }}" id="txtID{{ $value->行番号 }}" value={{ $value->id }}>
                <!-- t年月日  -->
                  <td><input name="txtYMD{{ $value->行番号 }}" id="txtYMD{{ $value->行番号 }}" tabindex={{ 1+ ($value->行番号-1) * $intKSuu }}  type="date" value={{ $value->年月日 }} required @if($value->行番号=='1') autofocus  @endif></th>
                  <!-- t顧客 -->
                  <td><select name="cmKokyaku{{ $value->行番号 }}" id="cmKokyaku{{ $value->行番号 }}" class="Css_ComboBox"  tabindex={{ 2+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtKokyaku as $s_value)
                          @if($value->顧客コード == $s_value->code  || Cookie::get('cmKokyaku') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t検査数	-->
                  <td><input name="nbKensa{{ $value->行番号 }}" id="nbKensa{{ $value->行番号 }}" type="text" id="form-ticker-symbol" maxlength="8" style="text-align:right"  tabindex={{ 3+ ($value->行番号-1) * $intKSuu }} value={{ $value->検査数 }}></td>
                  <!-- t不適合内容 -->
                  <td><textarea name="txtFutekigou{{ $value->行番号 }}" id="txtFutekigou{{ $value->行番号 }}" rows="1" maxlength="300"  tabindex={{ 4+ ($value->行番号-1) * $intKSuu }}>{{ $value->不適合内容 }}</textarea></td>
                  <!-- t重大レベル	-->
                  <td><select name="cmZyuudai{{ $value->行番号 }}" id="cmZyuudai{{ $value->行番号 }}" class="Css_ComboBox"  tabindex={{ 5+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtZyuudai as $s_value)
                          @if($value->重大レベルコード == $s_value->code  ||  Cookie::get('cmZyuudai') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t物的要因	-->
                  <td><select name="cmButteki{{ $value->行番号 }}" id="cmButteki{{ $value->行番号 }}" class="Css_ComboBox"  tabindex={{ 6+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtButteki as $s_value)
                          @if($value->物的要因コード == $s_value->code  ||  Cookie::get('cmButteki') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t発見	-->
                  <td><select name="cmHakken{{ $value->行番号 }}" id="cmHakken{{ $value->行番号 }}" class="Css_ComboBox"  tabindex={{ 7+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtHakken as $s_value)
                          @if($value->発見コード == $s_value->code  ||  Cookie::get('cmHakken') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t備考 -->
                  <td rowspan=2><textarea class="textarea_2" name="txtBikou{{ $value->行番号 }}"  id="txtBikou{{ $value->行番号 }}" rows="2" maxlength="300"  tabindex={{ 8+ ($value->行番号-1) * $intKSuu }}>{{ $value->備考 }}</textarea></td>
              </tr>

                <tr>
                  <!-- t不適合製品タグNo	 -->
                  <td><input type="text" name="txtFutekiNo{{ $value->行番号 }}" id="txtFutekiNo{{ $value->行番号 }}" maxlength="50" inputmode="url"  tabindex={{ 9+ ($value->行番号-1) * $intKSuu }} value={{ $value->不適合製品タグNo }} ></td>
                  <!-- t不適合区分 -->
                  <td><select name="cmFutekigouKubun{{ $value->行番号 }}" id="cmFutekigouKubun{{ $value->行番号 }}" class="Css_ComboBox"  tabindex={{ 10+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtFutekigouKubun as $s_value)
                          @if($value->不適合区分コード == $s_value->code  ||  Cookie::get('cmFutekigouKubun') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t不適合数 -->
                  <td><input name="nbFutekigouSuu{{ $value->行番号 }}" id="nbFutekigouSuu{{ $value->行番号 }}" type="text" id="form-ticker-symbol" maxlength="8" style="text-align:right"  tabindex={{ 11+ ($value->行番号-1) * $intKSuu }} value={{ $value->不適合数 }}></td>
                  <!-- t原因 -->
                  <td><textarea name="txtGennin{{ $value->行番号 }}" id="txtGennin{{ $value->行番号 }}" rows="1" maxlength="300"  tabindex={{ 12+ ($value->行番号-1) * $intKSuu }}>{{ $value->原因 }}</textarea></td>
                  <!-- t処置 -->
                  <td><select name="cmSyochi{{ $value->行番号 }}" id="cmSyochi{{ $value->行番号 }}" class="Css_ComboBox"  tabindex={{ 13+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtSyochi as $s_value)
                          @if($value->処置コード == $s_value->code  ||  Cookie::get('cmSyochi') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t人的要因 -->
                  <td><select name="cmZinteki{{ $value->行番号 }}" id="cmZinteki{{ $value->行番号 }}" class="Css_ComboBox"  tabindex={{ 14+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtZinteki as $s_value)
                          @if($value->人的要因コード == $s_value->code  ||  Cookie::get('cmZinteki') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t発生－外注 -->
                  <td><select name="cmGaichyuu{{ $value->行番号 }}" id="cmGaichyuu{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 15+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtGaichyuu as $s_value)
                          @if($value->発注_外注コード == $s_value->code  ||  Cookie::get('cmGaichyuu') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                </tr>

                <tr>
                  <!-- t品名 -->
                  <td><input type="text" name="txtHinnmei{{ $value->行番号 }}" id="txtHinnmei{{ $value->行番号 }}" maxlength="50"  tabindex={{ 16+ ($value->行番号-1) * $intKSuu }} value={{ $value->品名 }}></td>
                  <!-- t品番 -->
                  <td><input type="text" name="txtHinban{{ $value->行番号 }}" id="txtHinban{{ $value->行番号 }}" maxlength="30"  tabindex={{ 17+ ($value->行番号-1) * $intKSuu }} value={{ $value->図面番号 }}></td>
                  <!-- tセリアルNo -->
                  <td><input type="text" name="txtSNO{{ $value->行番号 }}" id="txtSNO{{ $value->行番号 }}" maxlength="30"  tabindex={{ 18+ ($value->行番号-1) * $intKSuu }} value={{ $value->セリアルNo }}></td>
                  <!-- t是正処置 -->
                  <td rowspan=1><textarea name="txtZeseisyoti{{ $value->行番号 }}" id="txtZeseisyoti{{ $value->行番号 }}" rows="1" maxlength="300"  tabindex={{ 19+ ($value->行番号-1) * $intKSuu }}>{{ $value->是正処置 }}</textarea></td>
                  <!-- t各No -->
                  <td><input type="text" name="txtKakuNo{{ $value->行番号 }}" id="txtKakuNo{{ $value->行番号 }}" maxlength="25"  tabindex={{ 20+ ($value->行番号-1) * $intKSuu }} value={{ $value->各No }}></td>
                  <!-- t特採申請No -->
                  <td><input type="text" name="txtTokusaiNo{{ $value->行番号 }}" id="txtTokusaiNo{{ $value->行番号 }}" maxlength="25" tabindex={{ 21+ ($value->行番号-1) * $intKSuu }} value={{ $value->特採申請No }}></td>
                  <!-- t発生－社内 -->
                  <td><select name="cmSyain{{ $value->行番号 }}" id="cmSyain{{ $value->行番号 }}" class="Css_ComboBox" tabindex={{ 22+ ($value->行番号-1) * $intKSuu }}>
                      <option value="">
                      @foreach($dtSyain as $s_value)
                          @if($value->発生_社内コード == $s_value->code  ||  Cookie::get('cmSyain') == $s_value->code )
                          <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
                          @else
                          <option value="{{$s_value->code}}">{{$s_value->name}}</option>
                          @endif
                      @endforeach
                  </select></td>
                  <!-- t是正処置実施確認日 -->
                  <td><input name="txZeseiYMD{{ $value->行番号 }}" id="txZeseiYMD{{ $value->行番号 }}" type="date" tabindex={{ 22+ ($value->行番号-1) * $intKSuu }} value={{ $value->是正処置実施確認日 }} required></td>
                </tr>
            </tbody>
          @endforeach

        </table>
        <!-- </section> -->

      </div>
        </div>
        <br clear="left"><!-- テーブルの下に回り込む -->
        <div class="Button_S Button_S_Waku" tabindex="-1">
          @csrf
          <button type="button" id="Tbutton2" onclick="Fnsubmit();" class="btn btn-primary" tabindex="-1">登録</button>
          @if($strTitle == '検索')
            <button type="button" id="Cbutton2" class="btn btn-primary" onclick="location.href='{{ route('mzwp0011') }}'" tabindex="-1">戻る</button>
          @else
            <button type="button" id="Cbutton2" class="btn btn-primary" onclick="clearForm()" tabindex="-1">取消</button>
          @endif
        </div>
        </div>
        <!-- <script>
        //Enterキーでフォーカス移動
        $(function() {
          $('input').on("keydown", function(e) {
              var n = $("input").length;
              if (e.which == 13) {
                  e.preventDefault();
                  var Index = $('input').index(this);
                  var nextIndex = $('input').index(this) + 1;
                  if (nextIndex < n) {
                      $('input')[nextIndex].focus();   // 次の要素へフォーカスを移動
                  } else {
                      $('input')[Index].blur();        // 最後の要素ではフォーカスを外す
                  }
              }
          });
      });
      </script> -->
        </div>
      </body>
    </div><!-- 幅固定用 -->
  </form>
@endsection


<script src="{{asset('js/commonTools.js')}}"></script>
<script>
  function Fnsubmit(){
    if (confirm("登録処理を行います。よろしいですか？")) {
      //ボタンロック
      Fnbutton_disabled(true);
      // jaxS準備
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
              'Content-Type': 'application/x-www-form-urlencoded'
          }
      });
      // 
      var id = "txtMaxData";
      let intMax=document.getElementById(id).value;
      for (let step = 1; step <= intMax; step++) {
        var id = "txtFutekiNo"+step;
        data=document.getElementById(id).value;
        if (data==""){
          alert("不適合製品タグNoを入力してください。");
          document.getElementById(id).focus();
          //ボタンロック解除
          Fnbutton_disabled(false);
          return false;
        }
        // if (FnisHanEisu_Check(data)==false){
        //   alert("不適合製品タグNoは半角英数値で入力してください。");
        //   document.getElementById(id).focus();
        //   return false;
        // }
        var id = "nbKensa"+step;
        data=document.getElementById(id).value;
        if (data!=""&&FnisHanSu_Check(data)==false){
          alert("検査数は半角数値で入力してください。");
          document.getElementById(id).focus();
          //ボタンロック解除
          Fnbutton_disabled(false);
          return false;
        }
        var id = "nbFutekigouSuu"+step;
        data=document.getElementById(id).value;
        if (data!=""&&FnisHanSu_Check(data)==false){
          alert("不適合数は半角数値で入力してください。");
          document.getElementById(id).focus();
          //ボタンロック解除
          Fnbutton_disabled(false);
          return false;
        }
        var id = "txtHinnmei"+step;
        data=document.getElementById(id).value;
        if (data==""){
          alert("品名を入力してください。");
          document.getElementById(id).focus();
          //ボタンロック解除
          Fnbutton_disabled(false);
          return false;
        }
        var id = "txtFutekigou"+step;
        data=document.getElementById(id).value;
        if (data==""){
          alert("不適合内容を入力してください。");
          document.getElementById(id).focus();
          //ボタンロック解除
          Fnbutton_disabled(false);
          return false;
        }
        // jaxS図面番号マスターチェック
        $strMsg = "";
        var id = "txtHinban"+step;
        data=document.getElementById(id).value;
        if (data==""){
          alert("図面番号を入力してください。");
          document.getElementById(id).focus();
          //ボタンロック解除
          Fnbutton_disabled(false);
          return false;
        }
        $.ajax({
          async: false,
          url: "{{asset('hinban_check')}}",
          type: "post",
          contentType: false,
          data: {
            hinban: data
          },
          dataType: "text",
        }).done((data1, textStatus, jqXHR) => {
            $strMsg = data1;
        }).fail((jqXHR, textStatus, errorThrown) => {
            $strMsg= errorThrown;
        }).always((jqXHR, textStatus) => {
        });

        if($strMsg!=""){
            if($strMsg!="OK"){
              alert($strMsg);
              document.getElementById(id).focus();
              //ボタンロック解除
              Fnbutton_disabled(false);
              return false;
            }
          }
        // jaxS図面番号マスターチェックEnd
      }
      // alert("submit");
        dispLoading();
        $('#mzwp0010').submit();
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
  //   $('#mzwp0010').submit(function(){
  //     alert("submit");
  //       dispLoading();
  //   }
  // );

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

        var id = "txYMD"+intGyou;
        document.getElementById(id).value=today;
        var id = "cmKokyaku"+intGyou;
        document.getElementById(id).value= "";
        var id = "nbKensa"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtFutekigou"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmZyuudai"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmButteki"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmHakken"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtBikou"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtTekiyou"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtFutekiNo"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmFutekigouKubun"+intGyou;
        document.getElementById(id).value= "";
        var id = "nbFutekigouSuu"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtGennin"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmSyochi"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmZinteki"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmGaichyuu"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtHinnmei"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtHinban"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtSNO"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtZeseisyoti"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtKakuNo"+intGyou;
        document.getElementById(id).value= "";
        var id = "txtTokusaiNo"+intGyou;
        document.getElementById(id).value= "";
        var id = "cmSyain"+intGyou;
        document.getElementById(id).value= "";
        var id = "txZeseiYMD"+intGyou;
        document.getElementById(id).value= "";
    }
}

// function onclick_Check(){
//     // 登録か取消かチェックする
//     if (document.getElementById('txtonclick_Check').value== '1') {
//         document.getElementById('txtonclick_Check').value= '';
//         return false;
//     }else{
//         return true;
//     }
// }

</script>