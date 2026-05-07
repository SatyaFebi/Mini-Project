<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
   protected $table = 'penjualans';

   protected $fillable = [
      'ID_NOTA',
      'TGL',
      'KODE_PELANGGAN',
      'SUBTOTAL'
   ];

   public function idNota() {
      return $this->belongsTo(ItemPenjualan::class, 'ID_NOTA', 'id');
   }

   public function kodePelanggan() {
      return $this->belongsTo(Pelanggan::class, 'KODE_PELANGGAN', 'id');
   }
}
