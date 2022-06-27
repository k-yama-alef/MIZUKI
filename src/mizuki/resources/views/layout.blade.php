@extends('layouts.app')

@section('content')
<!-- <div class="container"> -->
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <!-- <link rel="stylesheet" href="{{asset('css/mzcommon.css?'.time())}}"> -->
        <script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
        <!-- <script src="{{asset('js/commonTools.js')}}"></script> -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="{{ asset('image/logo-pc.ico') }}">
    </head>
    <script src="{{asset('js/commonTools.js')}}"></script>
    <div class="row justify-content-start">
        <div class="col-md-12">
            <!-- <div class="card"> -->
                <div class="card-header">{{ Auth::user()->name_m }} さんログイン中</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @else
                        @yield('child')
                    @endif
                </div>
            <!-- </div> -->
        </div>
    </div>
</html>
  <!-- </div> -->
@endsection
