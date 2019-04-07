@extends('layouts.web')

@section('content')

    @if($appStatus == 'OFF')
        <notwork-component appkey="{{ $appKey }}"></notwork-component>
    @else
        <profile-component appkey="{{ $appKey }}"></profile-component>
    @endif

@endsection
