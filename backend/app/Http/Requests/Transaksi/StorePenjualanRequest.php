<?php

namespace App\Http\Requests\Transaksi;

use Illuminate\Foundation\Http\FormRequest;

class StorePenjualanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ID_PELANGGAN' => 'required|exists:pelanggans,ID_PELANGGAN',
            'TGL' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.KODE' => 'required|string',
            'items.*.QTY' => 'required|integer|min:1',
        ];
    }
}
