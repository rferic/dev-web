@extends('adminlte::page')

@section('title', 'CoffeCode')

@section('content_header')
    <h1>{{ __('Apps') }}</h1>
@stop

@section('content')
    @if (COUNT($apps) > 0)
        <apps-list
    		locale="{{ LaravelLocalization::getCurrentLocale() }}"
    		apps_json="{{ json_encode($apps) }}"
    		types_json="{{ json_encode($types) }}"
    		status_json="{{ json_encode($status) }}"
        ></apps-list>
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/admin/admin.js') }}"></script>
@endsection
