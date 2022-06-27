@extends('layout')
<!-- 以下(layout.blade.php)で表示 -->
@section('child')
{{-- 品質管理 --}}
<form id="mzwp0011" action="{{ route('mzwp0010_read') }}" method="get">
    <div class="Itiran-OoWaku"><!-- 幅固定用 -->
      @if ($intDataKubun==1) 
        <div class="alert alert-danger">データがありません。</div>
      @endif
      <div id="recent-history">
        <a href="{{ route('home') }}">ホームへ</a>
        <div class="row py-2">
          <div class="col">
          <h2>品質管理--{{ $strTitle }}</h2>
          </div>
        </div>
        <div class="Button_S Button_S_Waku">
          @csrf
          <button type="button" onclick="Fnsubmit();" class="btn btn-primary" tabindex="-1">検索</button>
          <button type="button" class="btn btn-primary" onclick="clearForm()" tabindex="-1">取消</button>
        </div>
      </div>
      <br>
      <font size=3>
      <tr><td>抽出期間：　開始</td>
      <input name="txYMD_S" id="txYMD_S"  type="date" tabindex=1 autofocus value={{$itemvalues->年月日S}} ></td>
        <td>～</td>
        <td>終了</td>
        <td><input name="txYMD_E" id="txYMD_E"  type="date" tabindex=2 value={{$itemvalues->年月日E}}></td></tr><br><br>
        （注記）抽出期間が空白の場合は、全期間を検索します。<br>
        <!-- <tr><td>抽出分類：　</td><td><input type="checkbox" name="分類Z"></td><td>Z:全表示</td><td><input type="checkbox" name="分類Q"></td><td>Q:不適合</td><td><input type="checkbox" name="分類R"></td><td>R:Rework</td><td><input type="checkbox" name="分類A"></td><td>A:情報</td><td>　（注記）　チェックボックス無選択は「不適合」の選択と自動的になります。</td></tr><br><br> -->
      <!-- <hr> -->
      </font>

      <body>
      <link rel="stylesheet" href="{{asset('css/mzcommon.css?'.time())}}">
      <input type="hidden" name="txtTitle" id="txtTitle" value={{ $strTitle }}>
      <table border="0" align="left" height="600">
        <td>顧客別</td>
        <td><select name="cmKokyaku" id="cmKokyaku" class="Css_ComboBox" tabindex=3>
          <option value=""></option>
          @foreach($dtKokyaku as $s_value)
          @if($itemvalues->顧客コード == $s_value->code  ||  Cookie::get('cmKokyaku') == $s_value->code )
            <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
          @else
            <option value="{{$s_value->code}}">{{$s_value->name}}</option>
          @endif
          @endforeach
          </select></td></tr>
        <td>重大レベル</td>
        <td><select name="cmZyuudai" id="cmZyuudai" class="Css_ComboBox" tabindex=4>
          <option value=""></option>
          @foreach($dtZyuudai as $s_value)
          @if($itemvalues->重大レベルコード == $s_value->code  ||  Cookie::get('cmZyuudai') == $s_value->code )
            <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
          @else
            <option value="{{$s_value->code}}">{{$s_value->name}}</option>
          @endif
          @endforeach
        </select></td></tr>
        <td>不適合部品タグNo</td><td><input type="text" name="txtFutekiNo" id="txtFutekiNo" maxlength="50" style=”ime-mode:disabled;” tabindex=5 value={{$itemvalues->不適合製品タグNo}}></td></tr>
        <td>外注別</td>
        <td><select name="cmGaichyuu" id="cmGaichyuu" class="Css_ComboBox" tabindex=6>
          <option value=""></option>
            @foreach($dtGaichyuu as $s_value)
            @if($itemvalues->発注_外注コード == $s_value->code  ||  Cookie::get('cmGaichyuu') == $s_value->code )
              <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
            @else
              <option value="{{$s_value->code}}">{{$s_value->name}}</option>
            @endif
            @endforeach
          </select></td></tr>
        <td>社員別</td>
        <td><select name="cmSyain" id="cmSyain" class="Css_ComboBox" tabindex=7>
            <option value=""></option>
            @foreach($dtSyain as $s_value)
            @if($itemvalues->発生_社内コード == $s_value->code  ||  Cookie::get('cmSyain') == $s_value->code )
              <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
            @else
              <option value="{{$s_value->code}}">{{$s_value->name}}</option>
            @endif
            @endforeach
          </select></td></tr>
        <td>部品番号別</td><td><input type="text" name="txtHinban" id="txtHinban" maxlength="30" tabindex=8 value={{$itemvalues->図面番号}}></td></tr>
        <td>S/No</td><td><input type="text" name="txtSNO" id="txtSNO" maxlength="30" tabindex=9 value={{$itemvalues->セリアルNo}}></td></tr>
        <td>不適合区分</td>
        <td><select name="cmFutekigouKubun" id="cmFutekigouKubun" class="Css_ComboBox" tabindex=10>
            <option value=""></option>
            @foreach($dtFutekigouKubun as $s_value)
            @if($itemvalues->不適合区分コード == $s_value->code  ||  Cookie::get('cmFutekigouKubun') == $s_value->code )
              <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
            @else
              <option value="{{$s_value->code}}">{{$s_value->name}}</option>
            @endif
            @endforeach
        </select></td><td>人的及び物的要因との組み合わせが可能です。</td></tr>
        <td>人的要因</td>
        <td><select name="cmZinteki" id="cmZinteki" class="Css_ComboBox" tabindex=11>
            <option value=""></option>
            @foreach($dtZinteki as $s_value)
            @if($itemvalues->人的要因コード == $s_value->code  ||  Cookie::get('cmZinteki') == $s_value->code )
              <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
            @else
              <option value="{{$s_value->code}}">{{$s_value->name}}</option>
            @endif
            @endforeach
        </select></td><td>不適合区分との組み合わせが可能です。</td></tr>

        <td>物的要因</td>
        <td><select name="cmButteki" id="cmButteki" class="Css_ComboBox" tabindex=12>
            <option value=""></option>
            @foreach($dtButteki as $s_value)
            @if($itemvalues->物的要因コード == $s_value->code  ||  Cookie::get('cmButteki') == $s_value->code )
              <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
            @else
              <option value="{{$s_value->code}}">{{$s_value->name}}</option>
            @endif
            @endforeach
            </select></td><td>不適合区分との組み合わせが可能です。</td></tr>
        <td>発見別</td>
        <td><select name="cmHakken" id="cmHakken" class="Css_ComboBox" tabindex=13>
            <option value=""></option>
            @foreach($dtHakken as $s_value)
            @if($itemvalues->発見コード == $s_value->code  ||  Cookie::get('cmHakken') == $s_value->code )
              <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
            @else
              <option value="{{$s_value->code}}">{{$s_value->name}}</option>
            @endif
            @endforeach
            </select></td><td>「顧客」との組み合わせ選択が出来ます。</td></tr>
        <td>処置別</td>
        <td><select name="cmSyochi" id="cmSyochi" class="Css_ComboBox" tabindex=14>
            <option value=""></option>
            @foreach($dtSyochi as $s_value)
            @if($itemvalues->処置コード == $s_value->code  ||  Cookie::get('cmSyochi') == $s_value->code )
              <option value="{{$s_value->code}}" selected>{{$s_value->name}}</option>
            @else
              <option value="{{$s_value->code}}">{{$s_value->name}}</option>
            @endif
            @endforeach
            </select></td></tr>
        <td>是正処置（I-TAG）No</td><td><textarea name="txtZeseisyoti" id="txtZeseisyoti" rows="1" maxlength="300" tabindex=15>{{$itemvalues->是正処置}}</textarea><td>半角英数字で入力（先頭数文字入力も可）</td></tr>
        <td>是正処置実施効果確認日</td><td><input name="txZeseiYMD" id="txZeseiYMD" type="date" tabindex=16 value={{$itemvalues->是正処置実施確認日}}></td>
        <td><input type="checkbox" name="chZeseiYMD" id="chZeseiYMD" tabindex=17 value="1" @if($itemvalues->是正処置実施確認日ch=='1') checked  @endif>是正処置実施効果確認日　空白のもの</td></tr>
        <td>特採申請書No</td><td><input type="text" name="txtTokusaiNo" id="txtTokusaiNo" maxlength="25" tabindex=18 value={{$itemvalues->特採申請No}}></td><td>半角英数字で入力</td></tr>
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
      $('#mzwp0011').submit();
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
    // $('#mzwp0011').submit(function(){
    //     dispLoading();
    // }
  // );

function clearForm(){
    // 画面をクリアする
    // // 隠しテキストに1を入れる
    // document.getElementById('txtonclick_Check').value= '1';

      var id = "txYMD_S";
      document.getElementById(id).value= "";
      var id = "txYMD_E";
      document.getElementById(id).value= "";
      var id = "cmKokyaku";
      document.getElementById(id).value= "";
      var id = "cmZyuudai";
      document.getElementById(id).value= "";
      var id = "cmGaichyuu";
      document.getElementById(id).value= "";
      var id = "txtFutekiNo";
      document.getElementById(id).value= "";
      var id = "cmSyain";
      document.getElementById(id).value= "";
      var id = "txtHinban";
      document.getElementById(id).value= "";
      var id = "txtSNO";
      document.getElementById(id).value= "";
      var id = "cmFutekigouKubun";
      document.getElementById(id).value= "";
      var id = "cmZinteki";
      document.getElementById(id).value= "";
      var id = "cmButteki";
      document.getElementById(id).value= "";
      var id = "cmHakken";
      document.getElementById(id).value= "";
      var id = "cmSyochi";
      document.getElementById(id).value= "";
      var id = "txtZeseisyoti";
      document.getElementById(id).value= "";
      var id = "txZeseiYMD";
      document.getElementById(id).value= "";
      var id = "chZeseiYMD";
      document.getElementById(id).value= "";
      var id = "txtTokusaiNo";
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