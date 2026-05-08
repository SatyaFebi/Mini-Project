<?php

namespace App\Http\Resources\Master;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PelangganResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_pelanggan' => $this->ID_PELANGGAN,
            'nama' => $this->NAMA,
            'domisili' => $this->DOMISILI,
            'jenis_kelamin' => $this->JENIS_KELAMIN,
            'created_at' => $this->created_at,
        ];
    }
}
