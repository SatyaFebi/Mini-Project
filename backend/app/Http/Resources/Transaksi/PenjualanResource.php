<?php

namespace App\Http\Resources\Transaksi;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Master\PelangganResource;
use App\Http\Resources\Transaksi\ItemPenjualanResource;

class PenjualanResource extends JsonResource
{
   /**
    * Transform the resource into an array.
    *
    * @return array<string, mixed>
    */
   public function toArray(Request $request): array
   {
      return [
         'id' => $this->id,
         'id_nota' => $this->ID_NOTA,
         'tgl' => $this->TGL,
         'kode_pelanggan' => $this->KODE_PELANGGAN,
         'subtotal' => $this->SUBTOTAL,
         'pelanggan' => new PelangganResource($this->whenLoaded('pelanggan')),
         'items' => ItemPenjualanResource::collection($this->whenLoaded('items')),
         'created_at' => $this->created_at
      ];
   }
}
