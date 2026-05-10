<?php

namespace App\Http\Controllers\Api\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Transaksi\StorePenjualanRequest;
use App\Http\Requests\Transaksi\UpdatePenjualanRequest;
use App\Http\Services\Transaksi\PenjualanService;
use App\Http\Resources\Transaksi\PenjualanResource;

class PenjualanController extends Controller
{
   public function __construct(
      protected PenjualanService $penjualanService
   ) {}

   public function index(Request $request)
   {
      $data = $this->penjualanService->getPenjualan($request->all());

      return PenjualanResource::collection($data)
         ->additional(['message' => 'Berhasil mengambil data penjualan']);
   }

   public function store(StorePenjualanRequest $request)
   {
      $data = $this->penjualanService->storePenjualan($request->validated());

      return (new PenjualanResource($data))
         ->additional(['message' => 'Berhasil menambahkan data penjualan'])
         ->response()
         ->setStatusCode(201);
   }

   public function update($id, UpdatePenjualanRequest $request)
   {
      $data = $this->penjualanService->updatePenjualan($id, $request->validated());

      return (new PenjualanResource($data))
         ->additional(['message' => 'Berhasil mengubah data penjualan'])
         ->response()
         ->setStatusCode(200);
   }

    public function show($id)
    {
        $data = $this->penjualanService->getPenjualanById($id);

        return (new PenjualanResource($data))
           ->additional(['message' => 'Berhasil mengambil detail penjualan']);
    }

    public function destroy($id)
    {
       $this->penjualanService->deletePenjualan($id);

       return response()->json([
          'success' => true,
          'message' => 'Berhasil menghapus data penjualan'
       ]);
    }
}
