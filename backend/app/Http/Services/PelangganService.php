<?php

namespace App\Http\Services;
use App\Models\Pelanggan;

class PelangganService {
   
   public function getAllPelanggan()
   {
      return Pelanggan::all();
   }

   public function createPelanggan(array $data)
   {
      return Pelanggan::create($data);
   }
}