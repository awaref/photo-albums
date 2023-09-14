@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.photo.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.photos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.photo.fields.id') }}
                        </th>
                        <td>
                            {{ $photo->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.photo.fields.name') }}
                        </th>
                        <td>
                            {{ $photo->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.photo.fields.photo') }}
                        </th>
                        <td>
                            @if($photo->photo)
                                <a href="{{ $photo->photo->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $photo->photo->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.photo.fields.album') }}
                        </th>
                        <td>
                            {{ $photo->album->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.photos.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection