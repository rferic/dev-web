@extends('adminlte::page')

@section('title', 'CoffeCode')

@section('content_header')
    <h1>{{ __('Profile') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <profile-form-update profile_json="{{ json_encode($user) }}" />
        </div>

        <div class="col-md-6 col-xs-12">
            <profile-form-reset />
        </div>
    </div>
@endsection

@section('script')
    <script>
        const routes = {
            routeProfileUpdate: "{{ route('admin.profile.update') }}",
            routeProfileReset: "{{ route('admin.profile.reset') }}",
            routeValidateEmailIsFree: "{{ route('admin.profile.emailIsFree') }}",
        }
    </script>
    <script src="{{ asset('js/admin/index.js') }}"></script>
@endsection
