<div class="col-md-6 col-sm-4 col-xs-12 col-md-offset-2 col-sm-offset-0">
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
                    <items-menu
                        locale="{{ $locale }}"
                        urlrequest="{{ route('admin.menu.getItemsLocale', $menu->id) }}"
                        loadingmessage="{{ __('Loading...') }}"
                        errormessage="{{ __('Error on request. Please, reload page') }}"
                    ></items-menu>
                </div>
            @endforeach
        </div>
    </div>
</div>
