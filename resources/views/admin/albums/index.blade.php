@extends('layouts.admin')
@section('content')
    @can('album_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.albums.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.album.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.album.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-Album">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>

                            <th>
                                {{ trans('cruds.album.fields.name') }}
                            </th>
                            <th>
                                {{ trans('cruds.photo.title') }}
                            </th>
                            <th>
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($albums as $key => $album)
                            <tr data-entry-id="{{ $album->id }}">
                                <td>

                                </td>

                                <td>
                                    {{ $album->name ?? '' }}
                                </td>
                                <td>
                                    {{ $album->albumPhotos->count() }}
                                </td>
                                <td>
                                    @can('album_show')
                                        <a class="btn btn-xs btn-primary" href="{{ route('admin.albums.show', $album->id) }}">
                                            {{ trans('global.view') }} {{ trans('cruds.photo.title') }}
                                        </a>
                                    @endcan

                                    @can('album_edit')
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.albums.edit', $album->id) }}">
                                            {{ trans('global.edit') }} {{ trans('cruds.album.title_singular') }}
                                        </a>
                                    @endcan

                                    @can('album_delete')

                                    @if($album->albumPhotos->count() > 0)
                                        <button class="btn btn-xs  btn-danger" data-toggle="modal" data-target="#deleteModal">
                                            {{ trans('global.delete') }} {{ trans('cruds.album.title_singular') }}
                                        </button>
                                    @else
                                      <form action="{{ route('admin.albums.destroy', $album->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }} {{ trans('cruds.album.title_singular') }}">
                                    </form>
                                    @endif
                                    @endcan

                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    @if($albums->count() > 0)
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">{{ trans('global.confirm_deletion') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ trans('global.are_you_sure_you_want_to_delete_this') }} {{ trans('cruds.album.title_singular') }}?
                    <br><br>
                    <div class="form-group">
                        <label for="delete_option">{{trans('global.choose_an_option') }}:</label>
                        <select class="form-control" id="delete_option" name="delete_option">
                            <option value="delete">{{ trans('global.delete_all') }} {{ trans('cruds.photo.title') }}</option>
                            <option value="move">{{trans('global.move') }} {{ trans('cruds.photo.title') }} {{ trans('global.to_another')}} {{ trans('cruds.album.title_singular') }}</option>
                        </select>
                    </div>

                    <!-- Additional content for moving pictures -->
                    <div class="form-group" id="move_album_select" style="display: none;">
                        <label for="move_to_album">{{trans('global.move') }} {{ trans('cruds.photo.title') }} {{ trans('global.to_another')}} {{ trans('cruds.album.title_singular') }}:</label>
                        <select class="form-control" id="move_to_album" name="move_to_album">
                            <option value="">{{ trans('global.select') }} {{ trans('cruds.album.title_singular') }}</option>
                            @foreach ($albums as $newalbum)
                                <option value="{{ $newalbum->id }}">{{ $newalbum->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('global.cancel') }}</button>
                    <form action="{{ route('admin.albums.destroy', $album->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" id="selected_option" name="delete_option" value="delete">
                        <input type="hidden" id="selected_album" name="selected_album" value="">
                        <button type="submit" class="btn btn-danger">{{ trans('global.confirm') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
@section('scripts')
    @parent
    <script>
        $(document).ready(function () {
            // Show/hide the "Move photos to another album" dropdown based on the selected option
            $('#delete_option').on('change', function () {
                var selectedOption = $(this).val();
                if (selectedOption === 'move') {
                    $('#move_album_select').show();
                } else {
                    $('#move_album_select').hide();
                }
                $('#selected_option').val(selectedOption);
            });

            // Update the selected album value when the user chooses an album to move pictures to
            $('#move_to_album').on('change', function () {
                var selectedAlbum = $(this).val();
                $('#selected_album').val(selectedAlbum);
            });
        });
    </script>
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            });
            let table = $('.datatable-Album:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
