@extends('layouts.web')

@section('content')

    @if($appStatus == 'OFF')
        <notwork-component appkey="{{ $appKey }}"></notwork-component>
    @else
        <index-component appkey="{{ $appKey }}"></index-component>
    @endif

@endsection
