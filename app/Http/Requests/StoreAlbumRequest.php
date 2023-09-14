<?php

namespace App\Http\Requests;

use App\Models\Album;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAlbumRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('album_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
        ];
    }
}
