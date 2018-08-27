@extends('adminlte::page')

@section('title', 'CoffeCode')

@section('content_header')
    <h1>{{ __('Apps administration') }}</h1>
@stop

@section('content')
    @if (COUNT($apps) > 0)
        <modal-dynamic component=""></modal-dynamic>
        <apps-list
    		apps_json="{{ json_encode($apps) }}"
    		types_json="{{ json_encode($types) }}"
    		status_json="{{ json_encode($status) }}"
        />
    @endif
@endsection

@section('script')
    <script>
        const routes = {
            routeAppStore: "{{ route('admin.app.store') }}",
            routesAppUpdate: {
                @foreach ($apps as $app)
                    "{{ $app->id }}": "{{ route('admin.app.update', $app->id) }}",
                @endforeach
            },
            routesAppDestroy: {
                @foreach ($apps as $app)
                    "{{ $app->id }}": "{{ route('admin.app.destroy', $app->id) }}",
                @endforeach
            },
            routeAppImagesUpload: "{{ route('admin.app-images.upload') }}",
            routeAppImagesDestroy: "{{ route('admin.app-images.destroy') }}"
        }
    </script>
    <script src="{{ asset('js/admin/index.js') }}"></script>
@endsection
