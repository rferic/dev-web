@extends('adminlte::page')

@section('title', 'CoffeCode')

@section('content_header')
    <h1>{{ __('Users in Private Apps') }}</h1>
@stop

@section('content')
    @if (COUNT($apps) > 0)
        <app-private-users-list
    		locale="{{ LaravelLocalization::getCurrentLocale() }}"
    		apps_json="{{ json_encode($apps) }}"
            users_json="{{ json_encode($users) }}"
        />
    @else
        <div class="alert alert-warning">{{ __("Private's Apps not found") }}</div>
    @endif
@endsection

@section('script')
    <script>
        const routes = {
            routesAppUserSync: {
                @foreach ($apps as $app)
                    "{{ $app->id }}": "{{ route('admin.apps.users.sync', $app->id) }}",
                @endforeach
            },
            routesAppUserRevoke: {
                @foreach ($apps as $app)
                    "{{ $app->id }}": "{{ route('admin.apps.users.revoke', $app->id) }}",
                @endforeach
            }
        }
    </script>
    <script src="{{ asset('js/admin/index.js') }}"></script>
@endsection
