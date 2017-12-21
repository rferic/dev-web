@extends('adminlte::page')

@section('title', 'CoffeCode')

@section('content_header')
    <h1>{{ __('Page') }}</h1>
@stop

@section('content')
    @include('admin.partials.message')
    @include('admin.partials.errors')

    <div class="col-md-12 col-sm-12 col-xs-12">
        <page-form
            page="{{ $page->id }}"
            locale="{{ LaravelLocalization::getCurrentLocale() }}"
            supported_locales_json="{{ json_encode(LaravelLocalization::getSupportedLocales()) }}"
            page_locales_json="{{ isset($page) ? json_encode($page->locales()->get()) : '[]'  }}"
            contents_json="{{ isset($page) ? json_encode($page->contents()->get()) : '[]'  }}"
        ></page-form>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/admin/admin.js') }}"></script>
@endsection
