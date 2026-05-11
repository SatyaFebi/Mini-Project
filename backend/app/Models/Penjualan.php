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
           $prefix = 'NOTA_';
           
           $latest = static::where('ID_NOTA', 'LIKE', $prefix . '%')
                           ->orderBy('id', 'desc')
                           ->first();
                           
           $num = 1;
           if ($latest) {
               $parts = explode('_', $latest->ID_NOTA);
               if (count($parts) == 2) {
                   $num = (int)$parts[1] + 1;
               }
           }
           $model->ID_NOTA = $prefix . $num;
       });
   }

   public function pelanggan() {
      return $this->belongsTo(Pelanggan::class, 'KODE_PELANGGAN', 'ID_PELANGGAN');
   }

   public function items() {
      return $this->hasMany(ItemPenjualan::class, 'NOTA', 'ID_NOTA');
   }
}
