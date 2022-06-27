@extends('layout')
<!-- 以下(layout.blade.php)で表示 -->
@section('child')
{{-- 文書管理 --}}
<form id="mzwp0031" action="{{ route('mzwp0030_read') }}" method="get">
    <div class="Itiran-OoWaku"><!-- 幅固定用 -->
      @if ($intDataKubun==1) 
        <div class="alert alert-danger">データがありません。</div>
      @endif
      <div id="recent-history">
        <a href="{{ route('home') }}">ホームへ</a>
        <div class="row py-2">
          <div class="col">
          <h2>文書管理--{{ $strTitle }}</h2>
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
        <td>題目</td>
        <td><input type="text" name="txtDaimoku" id="txtDaimoku" maxlength="50" tabindex=0 autofocus value={{ $itemvalues->題目 }}></td><td>曖昧検索も可能です。</td></tr> 
        <td>文書番号</td>
        <td><input type="text" name="txtBunsyoBangou" id="txtBunsyoBangou" maxlength="50"  value={{ $itemvalues->文書番号 }} ></td><td>曖昧検索が可能です。（例）MR-QC-の様に半角英数字で入力</td></tr>
        </tr>
        <td>大分類</td>
          <td><select name="cmDaibunnrui" id="cmDaibunnrui" class="Css_ComboBox">
              <option value=""></option>
              @foreach($dtDaibunnrui as $s_value)
              @if($itemvalues->大分類 == $s_value->code  ||  Cookie::get('cmDaibunnrui') == $s_value->code )
                <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
              @else
                <option value="{{$s_value->code}}">{{$s_value->name}}</option>
              @endif
              @endforeach
          </select></td></tr>
        <td>小分類</td>
          <td><select name="cmSyoubunnrui" id="cmSyoubunnrui" class="Css_ComboBox">
              <option value=""></option>
              @foreach($dtSyoubunnrui as $s_value)
              @if($itemvalues->小分類 == $s_value->code  ||  Cookie::get('cmSyoubunnrui') == $s_value->code )
                <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
              @else
                <option value="{{$s_value->code}}">{{$s_value->name}}</option>
              @endif
              @endforeach
          </select></td></tr>
        <td>保管期限</td><td><input name="txtHokanYMD" id="txtHokanYMD" type="date" value={{$itemvalues->保管期限}}></td></tr>
        <td>作成者</td>
        <td><select name="cmSakuseisya" id="cmSakuseisya" class="Css_ComboBox">
              <option value=""></option>
              @foreach($dtSyain as $s_value)
              @if($itemvalues->作成者 == $s_value->code  ||  Cookie::get('cmSakuseisya') == $s_value->code )
                <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
              @else
                <option value="{{$s_value->code}}">{{$s_value->name}}</option>
              @endif
              @endforeach
        </select></td></tr>
        </tr>
        <td>重要区分</td>
        <td><select name="cmZyuuyou" id="cmZyuuyou" class="Css_ComboBox">
            <option value=""></option>
              @foreach($dtZyuuyou as $s_value)
              @if($itemvalues->重要区分 == $s_value->code  ||  Cookie::get('cmZyuuyou') == $s_value->code )
                <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
              @else
                <option value="{{$s_value->code}}">{{$s_value->name}}</option>
              @endif
              @endforeach
        </select></td></tr>
        <td>キーワード</td><td><input type="text" name="txtKey" id="txtKey" maxlength="25" value={{$itemvalues->キーワード}}></td><td>入力した文字を「キーワード１～６」の中から検索します。（ORやANDは、使用できません。）</td></tr>       

        <td>作成年</td>
        <td><select name="cmYYYY" id="cmYYYY" class="Css_ComboBox">
              <option value=""></option>
              @foreach($dtYYYY as $s_value)
              @if($itemvalues->作成年 == $s_value->年  ||  Cookie::get('cmYYYY') == $s_value->年 )
                <option value="{{$s_value->年}}" selected>{{$s_value->年}}</option>
              @else
                <option value="{{$s_value->年}}">{{$s_value->年}}</option>
              @endif
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
      $('#mzwp0031').submit();
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
  //   $('#mzwp0031').submit(function(){
  //       dispLoading();
  //   }
  // );

function clearForm(){
    // 画面をクリアする
    // // 隠しテキストに1を入れる
    // document.getElementById('txtonclick_Check').value= '1';

      var id = "txtDaimoku";
      document.getElementById(id).value= "";
      var id = "txtBunsyoBangou";
      document.getElementById(id).value= "";
      var id = "cmDaibunnrui";
      document.getElementById(id).value= "";
      var id = "cmSyoubunnrui";
      document.getElementById(id).value= "";
      var id = "txtHokanYMD";
      document.getElementById(id).value= "";
      var id = "cmSakuseisya";
      document.getElementById(id).value= "";
      var id = "cmZyuuyou";
      document.getElementById(id).value= "";
      var id = "txtKey";
      document.getElementById(id).value= "";
      var id = "cmYYYY";
      document.getElementById(id).value= "";

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