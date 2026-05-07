<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelanggan;

class PelangganController extends Controller
{
   public function index(Request $request)
   {
      $search = $request->input('search');
      $perPage = $request->input('per_page');

      $data = Pelanggan::select('id', 'ID_PELANGGAN', 'NAMA', 'DOMISILI', 'JENIS_KELAMIN', 'created_at');

      if ($search) {
         $data->where('NAMA', 'LIKE', '%' . $search . '%')
            ->where('ID_PELANGGAN', 'LIKE', '%' . $search . '%');
      }

      $data = $data->paginate($perPage);

      return response()->json([
         'success' => true,
         'data' => $data
      ], 200);
   }
}
