<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelanggan extends Model
{
   use SoftDeletes;
   
   protected $table = 'pelanggans';

   protected $fillable = [
      'ID_PELANGGAN',
      'NAMA',
      'DOMISILI',
      'JENIS_KELAMIN'
   ];

   protected static function boot()
   {
       parent::boot();

       static::creating(function ($model) {
           $latest = static::orderBy('id', 'desc')->first();
           $num = 1;
           if ($latest) {
               $parts = explode('_', $latest->ID_PELANGGAN);
               if (count($parts) == 2) {
                   $num = (int)$parts[1] + 1;
               }
           }
           $model->ID_PELANGGAN = 'PELANGGAN_' . $num;
       });
   }

   public function penjualans()
   {
       return $this->hasMany(Penjualan::class, 'KODE_PELANGGAN', 'ID_PELANGGAN');
   }
}
