@if (COUNT($collection) > 0)
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    @if ($typeList === 'trashed')
                        <th>&nbsp;</th>
                    @endif
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Title') }}</th>
                    <th >{{ __('Slug') }}</th>
                    <th >{{ __('Layout') }}</th>
                    <th>{{ __('Author') }}</th>
                    <th>{{ __('Updated') }}</th>
                    <th>{{ __('Created') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach ($collection as $key => $item)
                        <tr>
                            @if ($typeList === 'trashed')
                                <td>
                                    <a href="{{ route('admin.page.restore', $item['id']) }}"><i class="fa fa-mail-reply"></i></a>
                                </td>
                                <td>
                                    <a class="text-danger" href="{{ route('admin.page.destroy', $item['id']) }}"><i class="fa fa-trash"></i></a>
                                </td>
                            @else
                                <td>
                                    <a class="text-danger" href="{{ route('admin.page.trash', $item['id']) }}"><i class="fa fa-remove"></i></a>
                                </td>
                            @endif
                            <td>{{ $item['id'] }}</td>
                            <td><a href="{{ route('admin.page.detail', $item['id']) }}">{{ $item['title'] }}</a></td>
                            <td>
                                @foreach ($item['slugs'] as $lang => $slug)
                                    <div>({{ $lang }}) {{ $slug }}</div>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($item['layouts'] as $lang => $layout)
                                    <div>({{ $lang }}) {{ $layout }}</div>
                                @endforeach
                            </td>
                            <td><a href="{{ route('admin.admin', $item['author']->id) }}">{{ $item['author']->name }}</a></td>
                            <td>{{ $item['updated_at'] }}</td>
                            <td>{{ $item['created_at'] }}</td>
                        </tr>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
@else
    <div class="callout callout-warning">
        <p>{{ __('Menus not found') }}</p>
    </div>
@endif
