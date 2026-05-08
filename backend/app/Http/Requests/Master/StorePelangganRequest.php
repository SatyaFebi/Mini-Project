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
            'ID_PELANGGAN' => 'required|string|unique:pelanggans,ID_PELANGGAN',
            'NAMA' => 'required|string|max:255',
            'DOMISILI' => 'required|string|max:255',
            'JENIS_KELAMIN' => 'required|in:PRIA,WANITA',
        ];
    }
}
