@extends('adminlte::page')

@section('title', 'CoffeCode')

@section('content_header')
    <h1>{{ __('Page') }}</h1>
@stop

@section('content')
    @include('admin.partials.message')
    @include('admin.partials.errors')

    @if (!isset($page))
        <div class="alert alert-warning">{{ __('You will can add content when you have created the page') }}</div>
    @endif
    
    <div class="col-md-12 col-sm-12 col-xs-12">
        <page-form
            page="{{ isset($page) ? $page->id : '' }}"
            locale="{{ LaravelLocalization::getCurrentLocale() }}"
            supported_locales_json="{{ json_encode(LaravelLocalization::getSupportedLocales()) }}"
            page_locales_json="{{ isset($page) ? json_encode($page->locales()->get()) : '[]'  }}"
            contents_json="{{ isset($page) ? json_encode($page->contents()->get()) : '[]'  }}"
            routepages="{{ route('admin.pages') }}"
            routepageupdate="{{ isset($page) ? route('admin.page.update', $page->id) : route('admin.page.store') }}"
            routepagelocaledestroy="{{ isset($page) ? route('admin.pagelocale.destroyPageLocale', $page->id) : '' }}"
        ></page-form>
    </div>
@endsection

@section('script')
    <script src="{{ asset('js/admin/admin.js') }}"></script>
@endsection
