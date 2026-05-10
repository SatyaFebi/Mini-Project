<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use App\Http\Services\Master\BarangService;
use App\Http\Resources\Master\BarangResource;
use App\Http\Requests\Master\StoreBarangRequest;
use App\Http\Requests\Master\UpdateBarangRequest;

class BarangController extends Controller
{
   public function __construct(
      protected BarangService $barangService
   ) {}

   #[OA\Get(
       path: "/api/v1/master/barang",
       summary: "Get list of barang with pagination and search",
       tags: ["Master Barang"],
       parameters: [
           new OA\Parameter(name: "search", in: "query", description: "Search by kode or nama", required: false, schema: new OA\Schema(type: "string")),
           new OA\Parameter(name: "page", in: "query", description: "Page number", required: false, schema: new OA\Schema(type: "integer"))
       ],
       responses: [
           new OA\Response(response: 200, description: "Successful operation")
       ]
   )]
   public function index(Request $request)
   {
      $result = $this->barangService->getBarang($request->all());

      return BarangResource::collection($result)
         ->additional(['success' => true]);
   }

   #[OA\Post(
       path: "/api/v1/master/barang",
       summary: "Create a new barang",
       tags: ["Master Barang"],
       requestBody: new OA\RequestBody(
           required: true,
           content: new OA\JsonContent(
               required: ["kode", "nama", "kategori", "harga"],
               properties: [
                   new OA\Property(property: "kode", type: "string", example: "B001"),
                   new OA\Property(property: "nama", type: "string", example: "Buku Tulis"),
                   new OA\Property(property: "kategori", type: "string", example: "ATK"),
                   new OA\Property(property: "harga", type: "integer", example: 15000)
               ]
           )
       ),
       responses: [
           new OA\Response(response: 201, description: "Barang berhasil ditambahkan"),
           new OA\Response(response: 422, description: "Validation Error")
       ]
   )]
   public function store(StoreBarangRequest $request)
   {
      $result = $this->barangService->storeBarang($request->validated());

      return (new BarangResource($result))
         ->additional(['message' => 'Barang berhasil ditambahkan'])
         ->response()
         ->setStatusCode(201);
   }

   #[OA\Get(
       path: "/api/v1/master/barang/all",
       summary: "Get all barang without pagination",
       tags: ["Master Barang"],
       responses: [
           new OA\Response(response: 200, description: "Successful operation")
       ]
   )]
   public function all()
   {
       $data = $this->barangService->getAll();
       return BarangResource::collection($data)
           ->additional(['success' => true]);
   }

   #[OA\Get(
       path: "/api/v1/master/barang/{id}",
       summary: "Get a single barang by ID",
       tags: ["Master Barang"],
       parameters: [
           new OA\Parameter(name: "id", in: "path", description: "ID of barang", required: true, schema: new OA\Schema(type: "integer"))
       ],
       responses: [
           new OA\Response(response: 200, description: "Successful operation"),
           new OA\Response(response: 404, description: "Barang not found")
       ]
   )]
   public function show($id)
   {
       $result = $this->barangService->findById($id);
       return (new BarangResource($result))
           ->additional(['success' => true]);
   }

   #[OA\Put(
       path: "/api/v1/master/barang/{id}",
       summary: "Update an existing barang",
       tags: ["Master Barang"],
       parameters: [
           new OA\Parameter(name: "id", in: "path", description: "ID of barang", required: true, schema: new OA\Schema(type: "integer"))
       ],
       requestBody: new OA\RequestBody(
           required: true,
           content: new OA\JsonContent(
               properties: [
                   new OA\Property(property: "kode", type: "string", example: "B001"),
                   new OA\Property(property: "nama", type: "string", example: "Buku Tulis Updated"),
                   new OA\Property(property: "kategori", type: "string", example: "ATK"),
                   new OA\Property(property: "harga", type: "integer", example: 20000)
               ]
           )
       ),
       responses: [
           new OA\Response(response: 200, description: "Barang berhasil diupdate"),
           new OA\Response(response: 404, description: "Barang not found"),
           new OA\Response(response: 422, description: "Validation Error")
       ]
   )]
   public function update(UpdateBarangRequest $request, $id)
   {
       $result = $this->barangService->updateBarang($id, $request->validated());
       return (new BarangResource($result))
           ->additional(['message' => 'Barang berhasil diupdate'])
           ->response()
           ->setStatusCode(200);
   }

   #[OA\Delete(
       path: "/api/v1/master/barang/{id}",
       summary: "Delete a barang",
       tags: ["Master Barang"],
       parameters: [
           new OA\Parameter(name: "id", in: "path", description: "ID of barang", required: true, schema: new OA\Schema(type: "integer"))
       ],
       responses: [
           new OA\Response(response: 200, description: "Barang berhasil dihapus"),
           new OA\Response(response: 404, description: "Barang not found")
       ]
   )]
   public function destroy($id)
   {
       $this->barangService->deleteBarang($id);
       return response()->json([
           'success' => true,
           'message' => 'Barang berhasil dihapus'
       ]);
   }
}
