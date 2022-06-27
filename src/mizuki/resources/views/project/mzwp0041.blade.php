@extends('layout')
<!-- 以下(layout.blade.php)で表示 -->
@section('child')
{{-- 計測器 --}}
<form id="mzwp0041" action="{{ route('mzwp0040_read') }}" method="get">
    <div class="Itiran-OoWaku"><!-- 幅固定用 -->
      @if ($intDataKubun==1) 
        <div class="alert alert-danger">データがありません。</div>
      @endif
      <div id="recent-history">
        <a href="{{ route('home') }}">ホームへ</a>
        <div class="row py-2">
          <div class="col">
          <h2>計測器--{{ $strTitle }}</h2>
          </div>
        </div>
        <div class="Button_S Button_S_Waku">
          @csrf
          <button type="button" onclick="Fnsubmit();" class="btn btn-primary" tabindex="-1">検索</button>
          <button type="button" class="btn btn-primary" onclick="clearForm()" tabindex="-1">取消</button>
        </div>
      </div>
      <br>
      <body>
      <link rel="stylesheet" href="{{asset('css/mzcommon.css?'.time())}}">
      <input type="hidden" name="txtTitle" id="txtTitle" value={{ $strTitle }}>
      <table border="0" align="left" height="400">
      <td>管理種別</td>
          <td><select name="cmKanrisyubetu" id="cmKanrisyubetu" class="Css_ComboBox" tabindex=0 autofocus>
              <option value=""></option>
              @foreach($dtKanrisyubetu as $s_value)
              @if($itemvalues->種類別 == $s_value->code  ||  Cookie::get('cmKanrisyubetu') == $s_value->code )
                <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
              @else
                <option value="{{$s_value->code}}">{{$s_value->name}}</option>
              @endif
              @endforeach
          </select></td></tr>
        <td>管理番号</td>
        <td><input type="text" name="txtKanriBangou" id="txtKanriBangou" maxlength="30" value={{ $itemvalues->管理番号 }}></td></tr> 
        <td>名称・型式</td>
        <td><input type="text" name="txtMeisyouKatasiki" id="txtMeisyouKatasiki" maxlength="30" value={{ $itemvalues->名称型式 }}></td><td>番号部のみ半角英数字で入力</td></tr> 
        <td>製造番号(S/N)</td>
        <td><input type="text" name="txtSeizouBangou" id="txtSeizouBangou" maxlength="25" inputmode="url" value={{ $itemvalues->製造番号 }} ></td><td>曖昧検索が可能です。（例）MR-QC-の様に半角英数字で入力</td></tr>
        </tr>
        <td>校正区分</td>
          <td><select name="cmKouseiKubun" id="cmKouseiKubun" class="Css_ComboBox">
              <option value=""></option>
              @foreach($dtKouseiKubun as $s_value)
              @if($itemvalues->校正区分 == $s_value->code  ||  Cookie::get('cmKouseiKubun') == $s_value->code )
                <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
              @else
                <option value="{{$s_value->code}}">{{$s_value->name}}</option>
              @endif
              @endforeach
          </select></td></tr>
        <td>借用者</td>
        <td><select name="cmSyakuyou" id="cmSyakuyou" class="Css_ComboBox">
            <option value=""></option>
              @foreach($dtSyakuyou as $s_value)
              @if($itemvalues->借用者 == $s_value->code  ||  Cookie::get('cmSyakuyou') == $s_value->code )
                <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
              @else
                <option value="{{$s_value->code}}">{{$s_value->name}}</option>
              @endif
              @endforeach
        </select></td></tr>         
        <td>保管場所</td>
          <td><select name="cmHokan" id="cmHokan" class="Css_ComboBox">
              <option value=""></option>
              @foreach($dtHokan as $s_value)
              @if($itemvalues->保管場所 == $s_value->code  ||  Cookie::get('cmHokan') == $s_value->code )
                <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
              @else
                <option value="{{$s_value->code}}">{{$s_value->name}}</option>
              @endif
              @endforeach
        </select></td></tr>
        <td>測定範囲</td>
        <td><input type="text" name="txtSokuteiHanni" id="txtSokuteiHanni" maxlength="25" value={{ $itemvalues->測定範囲 }}></td><td></td></tr>
        <td>メーカ名</td>
        <td><select name="cmMaker" id="cmMaker" class="Css_ComboBox">
              <option value=""></option>
              @foreach($dtMaker as $s_value)
              @if($itemvalues->メーカ名 == $s_value->code  ||  Cookie::get('cmMaker') == $s_value->code )
                <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
              @else
                <option value="{{$s_value->code}}">{{$s_value->name}}</option>
              @endif
              @endforeach
        </select></td></tr>
        <td>登録日</td><td><input name="txtTourokuYMD" id="txtTourokuYMD" type="date" value={{$itemvalues->登録日}}>
          <input type="radio" name="opTourokuYMD" value="1" @if($itemvalues->登録日op=='1') checked  @endif>一致　
          <input type="radio" name="opTourokuYMD" value="2" @if($itemvalues->登録日op=='2') checked  @endif>以上　
          <input type="radio" name="opTourokuYMD" value="3" @if($itemvalues->登録日op=='3') checked  @endif>以下　
        </td></tr>
        <td>次回検定日</td><td><input name="txtZikaiKentei" id="txtZikaiKentei" type="date" value={{$itemvalues->次回検定日}}>
          <input type="radio" name="opZikaiKentei" value="1" @if($itemvalues->次回検定日op=='1') checked  @endif>一致　
          <input type="radio" name="opZikaiKentei" value="2" @if($itemvalues->次回検定日op=='2') checked  @endif>以上　
          <input type="radio" name="opZikaiKentei" value="3" @if($itemvalues->次回検定日op=='3') checked  @endif>以下　
        </td></tr>
        <td>検定実施日</td><td><input name="txtKenteiZissibi" id="txtKenteiZissibi" type="date" value={{$itemvalues->検定実施日}}>
          <input type="radio" name="opKenteiZissibi" value="1" @if($itemvalues->検定実施日op=='1') checked  @endif>一致　
          <input type="radio" name="opKenteiZissibi" value="2" @if($itemvalues->検定実施日op=='2') checked  @endif>以上　
          <input type="radio" name="opKenteiZissibi" value="3" @if($itemvalues->検定実施日op=='3') checked  @endif>以下　
        </td></tr>
        <td><input type="checkbox" name="chTeisi" id="chTeisi" value="1" @if($itemvalues->使用停止廃却処分品ch=='1') checked  @endif>使用停止、廃却処分品含む</td></tr>
        </tr>

        <td>検定期限件数（表示のみ）</td>
        <td><select name="cmKensuu" id="cmKensuu" class="Css_ComboBox">
              <option value=""></option>
              @foreach($dtKensuu as $s_value)
                <option value="{{$s_value->項目}}">{{$s_value->項目."  ".$s_value->件数."件数"}}</option>
              @endforeach
        </select></td></tr>
        </table>
        
        <br clear="left"><!-- テーブルの下に回り込む -->
        <div id="recent-history">
        <div class="Button_S Button_S_Waku">
          @csrf
          <button type="button" onclick="Fnsubmit();" class="btn btn-primary" tabindex="-1">検索</button>
          <button type="button" class="btn btn-primary" onclick="clearForm()" tabindex="-1">取消</button>
        </div>
        </div>
      </div>
      
    </body>
</form>
@endsection

<script>
  function Fnsubmit(){
      dispLoading();
      $('#mzwp0041').submit();
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
  //   $('#mzwp0041').submit(function(){
  //       dispLoading();
  //   }
  // );

function clearForm(){
    // 画面をクリアする
    // // 隠しテキストに1を入れる
    // document.getElementById('txtonclick_Check').value= '1';

      var id = "cmKanrisyubetu";
      document.getElementById(id).value= "";
      var id = "txtMeisyouKatasiki";
      document.getElementById(id).value= "";
      var id = "txtSeizouBangou";
      document.getElementById(id).value= "";
      var id = "cmKouseiKubun";
      document.getElementById(id).value= "";
      var id = "cmSyakuyou";
      document.getElementById(id).value= "";
      var id = "cmHokan";
      document.getElementById(id).value= "";
      var id = "txtSokuteiHanni";
      document.getElementById(id).value= "";
      var id = "cmMaker";
      document.getElementById(id).value= "";
      var id = "txtTourokuYMD";
      document.getElementById(id).value= "";
      // var id = "opTourokuYMD";
      // document.getElementById(id).value= "";
      var id = "txtZikaiKentei";
      document.getElementById(id).value= "";
      // var id = "opZikaiKentei";
      // document.getElementById(id).value= "1";
      var id = "txtKenteiZissibi";
      document.getElementById(id).value= "";
      // var id = "opKenteiZissibi";
      // document.getElementById(id).value= "1";
      var id = "chTeisi";
      document.getElementById(id).checked= "";

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