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
}
