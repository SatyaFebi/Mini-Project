<?php

namespace App\Http\Requests\Master;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBarangRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         return [
            'KODE' => 'nullable|string|max:255',
            'NAMA' => 'nullable|string|max:255',
            'KATEGORI' => 'nullable|in:ATK,MASAK,RT,ELEKTRONIK,LAINNYA',
            'HARGA' => 'nullable|integer',
         ];
    }
}
