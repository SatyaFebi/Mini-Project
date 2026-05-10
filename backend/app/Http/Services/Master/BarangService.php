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
              ->orWhere('NAMA', 'LIKE', '%' . $search . '%');
         });
      }

      return $query->paginate($perPage);
   }

   public function storeBarang(array $data)
   {
      return Barang::create($data);
   }

   public function getAll()
   {
       return Barang::all();
   }

   public function findById($id)
   {
       return Barang::findOrFail($id);
   }

   public function updateBarang($id, array $data)
   {
       $result = Barang::findOrFail($id);
       $result->update($data);
       return $result;
   }

   public function deleteBarang($id)
   {
       $result = Barang::findOrFail($id);
       return $result->delete();
   }
}