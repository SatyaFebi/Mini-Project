<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BarangResource extends JsonResource
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
            'kode' => $this->KODE,
            'nama' => $this->NAMA,
            'kategori' => $this->KATEGORI,
            'harga' => $this->HARGA,
            'created_at' => $this->created_at
        ];
    }
}
