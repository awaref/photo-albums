<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPhotoRequest;
use App\Http\Requests\StorePhotoRequest;
use App\Http\Requests\UpdatePhotoRequest;
use App\Models\Album;
use App\Models\Photo;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class PhotosController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('photo_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $photos = Photo::with(['album', 'media'])->get();

        $albums = Album::get();

        return view('admin.photos.index', compact('albums', 'photos'));
    }

    public function create()
    {
        abort_if(Gate::denies('photo_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $albums = Album::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.photos.create', compact('albums'));
    }

    public function store(StorePhotoRequest $request)
    {
        $photo = Photo::create($request->all());

        if ($request->input('photo', false)) {
            $photo->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $photo->id]);
        }

        return redirect()->route('admin.photos.index');
    }

    public function edit(Photo $photo)
    {
        abort_if(Gate::denies('photo_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $albums = Album::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $photo->load('album');

        return view('admin.photos.edit', compact('albums', 'photo'));
    }

    public function update(UpdatePhotoRequest $request, Photo $photo)
    {
        $photo->update($request->all());

        if ($request->input('photo', false)) {
            if (! $photo->photo || $request->input('photo') !== $photo->photo->file_name) {
                if ($photo->photo) {
                    $photo->photo->delete();
                }
                $photo->addMedia(storage_path('tmp/uploads/' . basename($request->input('photo'))))->toMediaCollection('photo');
            }
        } elseif ($photo->photo) {
            $photo->photo->delete();
        }

        return redirect()->route('admin.photos.index');
    }

    public function show(Photo $photo)
    {
        abort_if(Gate::denies('photo_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $photo->load('album');

        return view('admin.photos.show', compact('photo'));
    }

    public function destroy(Photo $photo)
    {
        abort_if(Gate::denies('photo_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $photo->delete();

        return back();
    }

    public function massDestroy(MassDestroyPhotoRequest $request)
    {
        $photos = Photo::find(request('ids'));

        foreach ($photos as $photo) {
            $photo->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('photo_create') && Gate::denies('photo_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Photo();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
