@extends('layouts.web')

@section('content')

    @if($appStatus == 'OFF')
        <notwork-component appkey="{{ $appKey }}"></notwork-component>
    @else
        <list-component appkey="{{ $appKey }}"></list-component>
    @endif

@endsection
