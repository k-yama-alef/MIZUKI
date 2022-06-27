@extends('layout')

  <!-- 以下(layout.blade.php)で表示 -->
  <body>
  @section('child')
  {{-- メニュー表示 --}}
  @if (count($menu_data) != 0)
    <div id="recent-history">
      <div class="row py-2">
        <div class="col">
        <h5>メニュー表示</h5>
        </div>
      </div>
    <div class="menu2">
        @foreach ($menu_data as $value)
          @if ($value['title'] != '')
            <!-- ※menu_barやlinksはapp.cssに設定がある -->
            <label class="menu2 label" for="menu_bar{{ $value['kubun'] }}">{{ $value['title'] }}</label>
            <input type="checkbox" id="menu_bar{{ $value['kubun'] }}" style="display:none" />
            <ul id="links{{ $value['kubun'] }}">
          @endif
          <li><a class="menu2 a" href="{{ $value['route'] }}" color: #000;>{{ $value['Name'] }}</a></li>
          @if ($value['end'] == 1)
            </ul>
          @endif
        @endforeach
      </div>
    </div>
    @endif
  @endsection
</body>