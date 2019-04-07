@extends('layouts.web')

@section('content')

    @if($appStatus == 'OFF')
        <notwork-component appkey="{{ $appKey }}"></notwork-component>
    @else
        <steps-component appkey="{{ $appKey }}" login="{{ $login }}"></steps-component>
    @endif

@endsection
