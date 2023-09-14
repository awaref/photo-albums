<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAlbumRequest;
use App\Http\Requests\StoreAlbumRequest;
use App\Http\Requests\UpdateAlbumRequest;
use App\Models\Album;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AlbumController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('album_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $albums = Album::all();

        return view('admin.albums.index', compact('albums'));
    }

    public function create()
    {
        abort_if(Gate::denies('album_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.albums.create');
    }

    public function store(StoreAlbumRequest $request)
    {
        $album = Album::create($request->all());

        return redirect()->route('admin.albums.index');
    }

    public function edit(Album $album)
    {
        abort_if(Gate::denies('album_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.albums.edit', compact('album'));
    }

    public function update(UpdateAlbumRequest $request, Album $album)
    {
        $album->update($request->all());

        return redirect()->route('admin.albums.index');
    }

    public function show(Album $album)
    {
        abort_if(Gate::denies('album_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $album->load('albumPhotos');

        return view('admin.albums.show', compact('album'));
    }

    // public function destroy(Album $album)
    // {
    //     abort_if(Gate::denies('album_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     $album->delete();

    //     return back();
    // }

    public function destroy(Request $request, $id)
    {
        $album = Album::findOrFail($id);

        if ($request->input('delete_option') === 'delete') {
            // Delete all photos in the album
            $album->albumPhotos()->delete();
        } elseif ($request->input('delete_option') === 'move') {
            $targetAlbumId = $request->input('selected_album');

            if (!empty($targetAlbumId)) {
                // Move photos to the target album
                $targetAlbum = Album::findOrFail($targetAlbumId);
                $photos = $album->albumPhotos;

                foreach ($photos as $photo) {
                    $photo->album_id = $targetAlbum->id;
                    $photo->save();
                }
            }
        }

        // Delete the album itself
        $album->delete();

        return back();
    }

}
