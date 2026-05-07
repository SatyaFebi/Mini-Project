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
            'NAMA' => 'nullable|string|max:255',
            'DOMISILI' => 'nullable|string|max:255',
            'JENIS_KELAMIN' => 'nullable|in:L,P',
        ];
    }
}
