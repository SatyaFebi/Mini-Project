<?php

namespace App\Http\Controllers\Api\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use App\Http\Requests\Transaksi\StorePenjualanRequest;
use App\Http\Requests\Transaksi\UpdatePenjualanRequest;
use App\Http\Services\Transaksi\PenjualanService;
use App\Http\Resources\Transaksi\PenjualanResource;

class PenjualanController extends Controller
{
   public function __construct(
      protected PenjualanService $penjualanService
   ) {}

   #[OA\Get(
       path: "/api/v1/transaksi/penjualan",
       summary: "Get list of penjualan with pagination and search",
       tags: ["Transaksi Penjualan"],
       parameters: [
           new OA\Parameter(name: "search", in: "query", description: "Search by ID Nota or Kode Pelanggan", required: false, schema: new OA\Schema(type: "string")),
           new OA\Parameter(name: "page", in: "query", description: "Page number", required: false, schema: new OA\Schema(type: "integer"))
       ],
       responses: [
           new OA\Response(response: 200, description: "Successful operation")
       ]
   )]
   public function index(Request $request)
   {
      $data = $this->penjualanService->getPenjualan($request->all());

      return PenjualanResource::collection($data)
         ->additional(['message' => 'Berhasil mengambil data penjualan']);
   }

   #[OA\Post(
       path: "/api/v1/transaksi/penjualan",
       summary: "Create a new penjualan transaction",
       tags: ["Transaksi Penjualan"],
       requestBody: new OA\RequestBody(
           required: true,
           content: new OA\JsonContent(
               required: ["tgl", "kode_pelanggan", "items"],
               properties: [
                   new OA\Property(property: "tgl", type: "string", format: "date", example: "2026-05-10"),
                   new OA\Property(property: "kode_pelanggan", type: "string", example: "PLG-001"),
                   new OA\Property(
                       property: "items", 
                       type: "array", 
                       items: new OA\Items(
                           properties: [
                               new OA\Property(property: "kode_barang", type: "string", example: "B001"),
                               new OA\Property(property: "qty", type: "integer", example: 2)
                           ]
                       )
                   )
               ]
           )
       ),
       responses: [
           new OA\Response(response: 201, description: "Berhasil menambahkan data penjualan"),
           new OA\Response(response: 422, description: "Validation Error")
       ]
   )]
   public function store(StorePenjualanRequest $request)
   {
      $data = $this->penjualanService->storePenjualan($request->validated());

      return (new PenjualanResource($data))
         ->additional(['message' => 'Berhasil menambahkan data penjualan'])
         ->response()
         ->setStatusCode(201);
   }

   #[OA\Put(
       path: "/api/v1/transaksi/penjualan/{id}",
       summary: "Update an existing penjualan transaction",
       tags: ["Transaksi Penjualan"],
       parameters: [
           new OA\Parameter(name: "id", in: "path", description: "ID of penjualan", required: true, schema: new OA\Schema(type: "integer"))
       ],
       requestBody: new OA\RequestBody(
           required: true,
           content: new OA\JsonContent(
               properties: [
                   new OA\Property(property: "tgl", type: "string", format: "date", example: "2026-05-11"),
                   new OA\Property(property: "kode_pelanggan", type: "string", example: "PLG-001"),
                   new OA\Property(
                       property: "items", 
                       type: "array", 
                       items: new OA\Items(
                           properties: [
                               new OA\Property(property: "kode_barang", type: "string", example: "B001"),
                               new OA\Property(property: "qty", type: "integer", example: 3)
                           ]
                       )
                   )
               ]
           )
       ),
       responses: [
           new OA\Response(response: 200, description: "Berhasil mengubah data penjualan"),
           new OA\Response(response: 404, description: "Penjualan not found"),
           new OA\Response(response: 422, description: "Validation Error")
       ]
   )]
   public function update($id, UpdatePenjualanRequest $request)
   {
      $data = $this->penjualanService->updatePenjualan($id, $request->validated());

      return (new PenjualanResource($data))
         ->additional(['message' => 'Berhasil mengubah data penjualan'])
         ->response()
         ->setStatusCode(200);
   }

    #[OA\Get(
        path: "/api/v1/transaksi/penjualan/{id}",
        summary: "Get detail of a penjualan transaction",
        tags: ["Transaksi Penjualan"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", description: "ID of penjualan", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Successful operation"),
            new OA\Response(response: 404, description: "Penjualan not found")
        ]
    )]
    public function show($id)
    {
        $data = $this->penjualanService->getPenjualanById($id);

        return (new PenjualanResource($data))
           ->additional(['message' => 'Berhasil mengambil detail penjualan']);
    }

    #[OA\Delete(
        path: "/api/v1/transaksi/penjualan/{id}",
        summary: "Delete a penjualan transaction",
        tags: ["Transaksi Penjualan"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", description: "ID of penjualan", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Berhasil menghapus data penjualan"),
            new OA\Response(response: 404, description: "Penjualan not found")
        ]
    )]
    public function destroy($id)
    {
       $this->penjualanService->deletePenjualan($id);

       return response()->json([
          'success' => true,
          'message' => 'Berhasil menghapus data penjualan'
       ]);
    }
}
