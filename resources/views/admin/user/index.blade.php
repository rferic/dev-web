@extends('adminlte::page')

@section('title', 'CoffeCode')

@section('content_header')
    <h1>{{ __('Pages') }}</h1>
@stop

@section('content')
    @include('admin.partials.message')

    <div class="col-md-12">
        <a href="{{ $role === 'public' ? route('admin.user.create') : route('admin.admin.create') }}" class="btn btn-app pull-right">
            <i class="fa fa-plus"></i> {{ __('Create') }}
        </a>
    </div>
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            @include('admin.user.partials.list')
        </div>
    </div>
@endsection
