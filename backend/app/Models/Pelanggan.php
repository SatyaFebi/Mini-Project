<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
   protected $table = 'pelanggans';

   protected $fillable = [
      'ID_PELANGGAN',
      'NAMA',
      'DOMISILI',
      'JENIS_KELAMIN'
   ];
}
