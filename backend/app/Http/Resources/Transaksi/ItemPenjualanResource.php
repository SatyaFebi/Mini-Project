<?php

namespace App\Http\Resources\Transaksi;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Master\BarangResource;
use Carbon\Carbon;

class ItemPenjualanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $harga = $this->relationLoaded('barang') ? $this->barang->HARGA : 0;
        

        return [
            'id' => $this->id,
            'nota' => $this->NOTA,
            'kode_barang' => $this->KODE_BARANG,
            'qty' => $this->Qty,
            'barang' => new BarangResource($this->whenLoaded('barang')),
            'subtotal_item' => $harga * $this->Qty,
            'penjualan' => $this->when($this->relationLoaded('penjualan'), function () {
                $penjualan = $this->penjualan;
                $carbonDate = Carbon::parse($penjualan->TGL)->timezone('Asia/Jakarta');
                $bulan = [
                    1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                ];
                
                return [
                    'id' => $penjualan->id,
                    'id_nota' => $penjualan->ID_NOTA,
                    'tgl' => $carbonDate->format('d') . ' ' . $bulan[$carbonDate->month] . ' ' . $carbonDate->format('Y'),
                    'subtotal' => $penjualan->SUBTOTAL,
                    'pelanggan' => $penjualan->relationLoaded('pelanggan') && $penjualan->pelanggan ? [
                        'id_pelanggan' => $penjualan->pelanggan->ID_PELANGGAN,
                        'nama' => $penjualan->pelanggan->NAMA,
                    ] : null,
                ];
            }),
        ];
    }
}
