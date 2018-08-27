@if ($collection->count() > 0)
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>{{ __('ID') }}</th>
                    <th >{{ __('Email') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Is banned') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @foreach ($collection as $key => $item)
                        <tr>
                            @if ($typeList === 'trashed')
                                <td>
                                    <a href="{{ route('admin.menu.restore', $item->id) }}"><i class="fa fa-mail-reply"></i></a>
                                </td>
                                <td>
                                    <a class="text-danger" href="{{ route('admin.menu.destroy', $item->id) }}"><i class="fa fa-trash"></i></a>
                                </td>
                            @else
                                <td>
                                    <a class="text-danger" href="{{ route('admin.menu.trash', $item->id) }}"><i class="fa fa-remove"></i></a>
                                </td>
                            @endif
                            <td>{{ $item->id }}</td>
                            <td><a href="{{ route('admin.menu.detail', $item->id) }}">{{ $item->name }}</a></td>
                            <td>{{ $item->description }}</td>
                            <td><a href="{{ route('admin.admin', $item->author->id) }}">{{ $item->author->name }}</a></td>
                            <td>{{ $item->created_at }}</td>
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
