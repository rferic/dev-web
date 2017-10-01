<div class="col-md-6 col-sm-4 col-xs-12 col-md-offset-2 col-sm-offset-0">
    ITEMS LOCALE
    <hr />
    @foreach ($itemsLocale AS $locale => $items)
        {{ $locale }}<br />
    @endforeach
</div>
