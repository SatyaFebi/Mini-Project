<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePelangganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'nullable|string|max:255',
            'domisili' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:PRIA,WANITA',
        ];
    }
}
