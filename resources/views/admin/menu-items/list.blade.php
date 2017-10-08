<div class="col-md-6 col-sm-4 col-xs-12 col-md-offset-2 col-sm-offset-0">
    <h2>{{ __('Items Menu') }}</h2>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            @foreach (LaravelLocalization::getSupportedLocales() AS $locale => $language)
                <li class="{{ $locale === LaravelLocalization::getCurrentLocale() ? 'active' : '' }}">
                    <a href="#{{ $locale }}" data-toggle="tab" aria-expanded="true">{{ $locale }}</a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content">
            @foreach (LaravelLocalization::getSupportedLocales() AS $locale => $language)
                <div class="tab-pane {{ $locale === LaravelLocalization::getCurrentLocale() ? 'active' : '' }}" id="{{ $locale }}">
                    <menu-drag-and-drop
                        menu="{{ $menu->id }}"
                        locale="{{ $locale }}"
                        routemenuget="{{ route('admin.menu.getItemsLocale', $menu->id) }}"
                        routemenusave="{{ route('admin.menu.save', $menu->id) }}"
                        routemenuitem="{{ route('admin.menu.items.create', $menu->id) }}"
                        routepageslist="{{ route('admin.pages.list') }}"
                        routepage="{{ url('/dev/pages/') }}"
                    ></menu-drag-and-drop>
                </div>
            @endforeach
        </div>
    </div>
</div>
<p class="clearfix"></p>
