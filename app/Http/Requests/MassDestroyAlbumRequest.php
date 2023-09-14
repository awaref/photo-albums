<?php

namespace App\Http\Requests;

use App\Models\Album;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyAlbumRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('album_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:albums,id',
        ];
    }
}
