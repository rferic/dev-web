@extends('adminlte::page')

@section('title', 'CoffeCode')

@section('content_header')
    <h1>{{ __('Pages') }}</h1>
@stop

@section('content')
    @include('admin.partials.message')

    <div class="col-md-12">
        <a href="{{ route('admin.page.create') }}" class="btn btn-app pull-right">
            <i class="fa fa-plus"></i> {{ __('Create') }}
        </a>
    </div>
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="{{ (!session('currentPanel') || session('currentPanel') === 'default') ? 'active' : '' }}"><a href="#available" data-toggle="tab" aria-expanded="true">{{ __('Available') }} ({{ COUNT($pages) }})</a></li>
                <li class="{{ (session('currentPanel') && session('currentPanel') === 'trash') ? 'active' : '' }}"><a href="#disable" data-toggle="tab" aria-expanded="false">{{ __('Disable') }} ({{ COUNT($pagesTrashed) }})</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane {{ (!session('currentPanel') || session('currentPanel') === 'default') ? 'active' : '' }}" id="available">
                    <?php $collection = $pages; ?>
                    <?php $typeList = 'default'; ?>
                    @include('admin.page.partials.list')
                </div>
                <div class="tab-pane {{ (session('currentPanel') && session('currentPanel') === 'trash') ? 'active' : '' }}" id="disable">
                    <?php $collection = $pagesTrashed; ?>
                    <?php $typeList = 'trashed'; ?>
                    @include('admin.page.partials.list')
                </div>
            </div>
        </div>
    </div>
@endsection
