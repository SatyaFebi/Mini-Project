<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
use App\Http\Resources\Master\PelangganResource;
use App\Http\Services\Master\PelangganService;
use App\Http\Requests\Master\StorePelangganRequest;
use App\Http\Requests\Master\UpdatePelangganRequest;

class PelangganController extends Controller
{
    public function __construct(
        protected PelangganService $pelangganService
    ) {}

    /**
     * Get list of pelanggan.
     */
    #[OA\Get(
        path: "/api/v1/master/pelanggan",
        summary: "Get list of pelanggan with pagination and search",
        tags: ["Master Pelanggan"],
        parameters: [
            new OA\Parameter(name: "search", in: "query", description: "Search by nama or ID", required: false, schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "page", in: "query", description: "Page number", required: false, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Successful operation")
        ]
    )]
    public function index(Request $request)
    {
        $data = $this->pelangganService->getPelanggan($request->all());

        return PelangganResource::collection($data)
            ->additional(['success' => true]);
    }

    #[OA\Post(
        path: "/api/v1/master/pelanggan",
        summary: "Create a new pelanggan",
        tags: ["Master Pelanggan"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["nama", "domisili", "jenis_kelamin"],
                properties: [
                    new OA\Property(property: "nama", type: "string", example: "Budi Santoso"),
                    new OA\Property(property: "domisili", type: "string", example: "Jakarta"),
                    new OA\Property(property: "jenis_kelamin", type: "string", enum: ["PRIA", "WANITA"], example: "PRIA")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Berhasil menambah pelanggan"),
            new OA\Response(response: 422, description: "Validation Error")
        ]
    )]
    public function store(StorePelangganRequest $request)
    {
         $result = $this->pelangganService->createPelanggan($request->validated());
         return (new PelangganResource($result))
               ->additional(['message' => 'Berhasil menambah pelanggan!'])
               ->response()
               ->setStatusCode(201);
    }

    #[OA\Put(
        path: "/api/v1/master/pelanggan/{id}",
        summary: "Update an existing pelanggan",
        tags: ["Master Pelanggan"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", description: "ID of pelanggan", required: true, schema: new OA\Schema(type: "integer"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "nama", type: "string", example: "Budi Santoso Updated"),
                    new OA\Property(property: "domisili", type: "string", example: "Bandung"),
                    new OA\Property(property: "jenis_kelamin", type: "string", enum: ["PRIA", "WANITA"], example: "PRIA")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Berhasil mengupdate pelanggan"),
            new OA\Response(response: 404, description: "Pelanggan not found"),
            new OA\Response(response: 422, description: "Validation Error")
        ]
    )]
    public function update(UpdatePelangganRequest $request, $id)
    {
         $result = $this->pelangganService->updatePelanggan($id, $request->validated());
         return (new PelangganResource($result))
            ->additional(['message' => 'Berhasil mengupdate pelanggan!'])
            ->response()
            ->setStatusCode(200);
    }

    #[OA\Get(
        path: "/api/v1/master/pelanggan/all",
        summary: "Get all pelanggan without pagination",
        tags: ["Master Pelanggan"],
        responses: [
            new OA\Response(response: 200, description: "Successful operation")
        ]
    )]
    public function all()
    {
        $data = $this->pelangganService->getAll();
        return PelangganResource::collection($data)
            ->additional(['success' => true]);
    }

    #[OA\Get(
        path: "/api/v1/master/pelanggan/{id}",
        summary: "Get a single pelanggan by ID",
        tags: ["Master Pelanggan"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", description: "ID of pelanggan", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Successful operation"),
            new OA\Response(response: 404, description: "Pelanggan not found")
        ]
    )]
    public function show($id)
    {
        $result = $this->pelangganService->findById($id);
        return (new PelangganResource($result))
            ->additional(['success' => true]);
    }

    #[OA\Delete(
        path: "/api/v1/master/pelanggan/{id}",
        summary: "Delete a pelanggan",
        tags: ["Master Pelanggan"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", description: "ID of pelanggan", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Berhasil menghapus pelanggan"),
            new OA\Response(response: 404, description: "Pelanggan not found")
        ]
    )]
    public function destroy($id)
    {
        $this->pelangganService->deletePelanggan($id);
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus pelanggan!'
        ]);
    }
}
