<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Master\BarangService;
use App\Http\Resources\Master\BarangResource;
use App\Http\Requests\Master\StoreBarangRequest;
use App\Http\Requests\Master\UpdateBarangRequest;

class BarangController extends Controller
{
   public function __construct(
      protected BarangService $barangService
   ) {}

   public function index(Request $request)
   {
      $result = $this->barangService->getBarang($request->all());

      return BarangResource::collection($result)
         ->additional(['success' => true]);
   }

   public function store(StoreBarangRequest $request)
   {
      $result = $this->barangService->storeBarang($request->validated());

      return (new BarangResource($result))
         ->additional(['message' => 'Barang berhasil ditambahkan'])
         ->response()
         ->setStatusCode(201);
   }

   public function all()
   {
       $data = $this->barangService->getAll();
       return BarangResource::collection($data)
           ->additional(['success' => true]);
   }

   public function show($id)
   {
       $result = $this->barangService->findById($id);
       return (new BarangResource($result))
           ->additional(['success' => true]);
   }

   public function update(UpdateBarangRequest $request, $id)
   {
       $result = $this->barangService->updateBarang($id, $request->validated());
       return (new BarangResource($result))
           ->additional(['message' => 'Barang berhasil diupdate'])
           ->response()
           ->setStatusCode(200);
   }

   public function destroy($id)
   {
       $this->barangService->deleteBarang($id);
       return response()->json([
           'success' => true,
           'message' => 'Barang berhasil dihapus'
       ]);
   }
}
