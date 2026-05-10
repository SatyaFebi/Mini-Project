<?php

namespace App\Http\Resources\Transaksi;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Master\BarangResource;

class ItemPenjualanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $harga = $this->relationLoaded('barang') ? $this->barang->HARGA : 0;
        
        return [
            'id' => $this->id,
            'kode_barang' => $this->KODE_BARANG,
            'qty' => $this->Qty,
            'barang' => new BarangResource($this->whenLoaded('barang')),
            'subtotal_item' => $harga * $this->Qty
        ];
    }
}
