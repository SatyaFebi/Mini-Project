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

   protected static function boot()
   {
       parent::boot();

       static::creating(function ($model) {
           $date = now()->format('Ymd');
           $prefix = 'INV-' . $date . '-';
           
           $latest = static::where('ID_NOTA', 'LIKE', $prefix . '%')
                           ->orderBy('id', 'desc')
                           ->first();
                           
           $num = 1;
           if ($latest) {
               $parts = explode('-', $latest->ID_NOTA);
               if (count($parts) == 3) {
                   $num = (int)$parts[2] + 1;
               }
           }
           $model->ID_NOTA = $prefix . str_pad($num, 3, '0', STR_PAD_LEFT);
       });
   }

   public function pelanggan() {
      return $this->belongsTo(Pelanggan::class, 'KODE_PELANGGAN', 'ID_PELANGGAN');
   }

   public function items() {
      return $this->hasMany(ItemPenjualan::class, 'NOTA', 'ID_NOTA');
   }
}
