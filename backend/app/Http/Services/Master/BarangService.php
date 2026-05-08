<?php

namespace App\Http\Services\Master;

use App\Models\Barang;

class BarangService {
   public function getBarang(array $params)
   {
      $search = $params['search'] ?? null;
      $perPage = $params['per_page'] ?? 10;

      $query = Barang::select('id', 'KODE', 'NAMA', 'KATEGORI', 'HARGA', 'created_at');

      if ($search) {
         $query->where(function ($q) use ($search) {
            $q->where('KODE', 'LIKE', '%' . $search . '%')
              ->orWhere('NAME', 'LIKE', '%' . $search . '%');
         });
      }

      return $query->paginate($perPage);
   }

   public function storeBarang(array $data)
   {
      return Barang::create($data);
   }
}