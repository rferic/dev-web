@extends('adminlte::page')

@section('title', 'CoffeCode')

@section('content_header')
    <h1>{{ __('Menus') }}</h1>
@stop

@section('content')
    @include('admin.partials.message')
    
    <div class="col-md-12">
        <a href="{{ route('admin.menu.create') }}" class="btn btn-app pull-right">
            <i class="fa fa-plus"></i> {{ __('Create') }}
        </a>
    </div>
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="{{ (!session('currentPanel') || session('currentPanel') === 'default') ? 'active' : '' }}"><a href="#available" data-toggle="tab" aria-expanded="true">{{ __('Available') }} ({{ $menus->count() }})</a></li>
                <li class="{{ (session('currentPanel') && session('currentPanel') === 'trash') ? 'active' : '' }}"><a href="#disable" data-toggle="tab" aria-expanded="false">{{ __('Disable') }} ({{ $menusTrashed->count() }})</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane {{ (!session('currentPanel') || session('currentPanel') === 'default') ? 'active' : '' }}" id="available">
                    <?php $collection = $menus; ?>
                    <?php $typeList = 'default'; ?>
                    @include('admin.menu.partials.list')
                </div>
                <div class="tab-pane {{ (session('currentPanel') && session('currentPanel') === 'trash') ? 'active' : '' }}" id="disable">
                    <?php $collection = $menusTrashed; ?>
                    <?php $typeList = 'trashed'; ?>
                    @include('admin.menu.partials.list')
                </div>
            </div>
        </div>
    </div>
@endsection
