<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Master\BarangService;
use App\Http\Resources\Master\BarangResource;
use App\Http\Requests\Master\StoreBarangRequest;

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
}
