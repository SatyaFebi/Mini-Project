<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class StorePelangganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'domisili' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:PRIA,WANITA',
        ];
    }
}
