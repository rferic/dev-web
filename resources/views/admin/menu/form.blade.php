@extends('adminlte::page')

@section('title', 'CoffeCode')

@section('content_header')
    <h1>{{ __('Menus') }}</h1>
@stop

@section('content')
    @include('admin.partials.message')
    @include('admin.partials.errors')

    <div class="col-md-4 col-sm-8 col-xs-12 {{ !isset($menu) ? 'col-md-offset-4 col-sm-offset-2 col-xs-offset-0' : '' }}">
        <h2>{{ __('Menu') }}</h2>
        <form class="form-horizontal" method="POST" action="{{ isset($menu) ? route('admin.menu.edit', $menu->id) : route('admin.menu.store') }}">
            {{ csrf_field() }}
            @if ($menu)
                {{ method_field("PUT") }}
            @endif
            <div class="form-group">
                <label class="control-label" for="email">{{ __('Name') }}:</label>
                <input type="text" class="form-control" name="name" placeholder="{{ __('Name') }}" value="{{ isset($menu) ? $menu->name : old('name') }}" required />
            </div>
            <div class="form-group">
                <label class="control-label text-right" for="description">{{ __('Description') }}:</label>
                <textarea type="text" class="form-control" name="description" placeholder="{{ __('Description') }}" value="{{ isset($menu) ? $menu->description : old('description') }}" required>{{ isset($menu) ? $menu->description : old('description') }}</textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-block btn-default">{{ __(isset($menu) ? 'Update' : 'Create') }}</button>
            </div>
            <p class="clearfix"></p>
        </form>
    </div>
    @if ($menu)
        <h2>{{ __('Items Menu') }}</h2>
        @include('admin.menu-items.list')
    @endif
@endsection

@section('script')
    @if ($menu)
        <script src="{{ asset('js/admin/admin.js') }}"></script>
    @endif
@endsection
