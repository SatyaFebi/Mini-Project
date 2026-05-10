<?php

namespace App\Http\Services\Transaksi;

use Illuminate\Support\Facades\DB;
use App\Models\Penjualan;
use App\Models\Barang;

class PenjualanService {
   public function getPenjualan(array $params)
   {
      $perPage = $params['per_page'] ?? 10;
      $search = $params['search'] ?? null;

      $query = Penjualan::with(['pelanggan', 'items.barang']);

      if ($search) {
         $query->where(function ($q) use ($search) {
            $q->where('ID_NOTA', 'LIKE', '%' . $search . '%')
               ->orWhere('KODE_PELANGGAN', 'LIKE', '%' . $search . '%');
         });
      }

      return $query->paginate($perPage);
   }

   public function getPenjualanById($id)
   {
       return Penjualan::with(['pelanggan', 'items.barang'])->findOrFail($id);
   }

   public function storePenjualan(array $data)
   {
      // Using transaction to avoid data mismatch with other related table
      DB::beginTransaction();

      try {
         $subtotal = 0;
         foreach ($data['items'] as $item) {
             $barang = Barang::where('KODE', $item['kode_barang'])->firstOrFail();
             $subtotal += $barang->HARGA * $item['qty'];
         }

         $penjualan = Penjualan::create([
            'TGL' => $data['tgl'],
            'KODE_PELANGGAN' => $data['kode_pelanggan'],
            'SUBTOTAL' => $subtotal
         ]);

         foreach ($data['items'] as $item) {
            $penjualan->items()->create([
               'KODE_BARANG' => $item['kode_barang'],
               'Qty' => $item['qty']
            ]);
         }

         DB::commit();

         return $penjualan->load(['pelanggan', 'items.barang']);

      } catch (\Exception $e) {
         DB::rollback();
         throw $e;
      }
   }

   public function updatePenjualan($id, array $data)
   {
      DB::beginTransaction();

      try {
         $penjualan = Penjualan::findOrFail($id);

         if (isset($data['tgl'])) $penjualan->TGL = $data['tgl'];
         if (isset($data['kode_pelanggan'])) $penjualan->KODE_PELANGGAN = $data['kode_pelanggan'];

         if (isset($data['items'])) {
             $penjualan->items()->delete();

             $subtotal = 0;
             foreach ($data['items'] as $item) {
                 $barang = Barang::where('KODE', $item['kode_barang'])->firstOrFail();
                 $subtotal += $barang->HARGA * $item['qty'];
                 
                 $penjualan->items()->create([
                    'KODE_BARANG' => $item['kode_barang'],
                    'Qty' => $item['qty']
                 ]);
             }
             $penjualan->SUBTOTAL = $subtotal;
         }

         $penjualan->save();

         DB::commit();

         return $penjualan->load(['pelanggan', 'items.barang']);

      } catch (\Exception $e) {
         DB::rollback();
         throw $e;
      }
   }

   public function deletePenjualan($id)
   {
      $result = Penjualan::findOrFail($id);
      $data = $result->load(['pelanggan', 'items.barang']);
      $result->delete();
      return $data;
   }
}