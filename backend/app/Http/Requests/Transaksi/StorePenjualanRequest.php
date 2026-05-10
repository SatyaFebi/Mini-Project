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
           'tgl' => 'required|date',
           'kode_pelanggan' => 'required|exists:pelanggans,ID_PELANGGAN',
           'items' => 'required|array|min:1',
           'items.*.kode_barang' => 'required|exists:barangs,KODE',
           'items.*.qty' => 'required|integer|min:1'
        ];
    }
}
