@extends('layouts.admin')
@section('content')


<div class="card">
    <div class="card-header">
         {{ trans('cruds.photo.title') }}  {{ $album->name }}
    </div>

    <div class="tab-content">
        <div class="tab-pane active" role="tabpanel" id="album_photos">
            @includeIf('admin.albums.relationships.albumPhotos', ['photos' => $album->albumPhotos])
        </div>
    </div>

</div>
<div class="form-group">
    <a class="btn btn-warning" href="{{ route('admin.albums.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>

@endsection
