@extends('layouts.web')

@section('content')

    @if($appStatus == 'OFF')
        <notwork-component appkey="{{ $appKey }}"></notwork-component>
    @else
        <login-component appkey="{{ $appKey }}"></login-component>
    @endif

@endsection
