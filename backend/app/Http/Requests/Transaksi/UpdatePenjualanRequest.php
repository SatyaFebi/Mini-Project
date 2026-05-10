<?php

namespace App\Http\Requests\Transaksi;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePenjualanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
         'tgl' => 'nullable|date',
         'kode_pelanggan' => 'nullable|exists:pelanggans,ID_PELANGGAN',
         'items' => 'nullable|array|min:1',
         'items.*.kode_barang' => 'required_with:items|exists:barangs,KODE',
         'items.*.qty' => 'required_with:items|integer|min:1'
        ];
    }
}
