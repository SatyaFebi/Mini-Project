<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
    public function index(Request $request)
    {
        $data = $this->pelangganService->getPelanggan($request->all());

        return PelangganResource::collection($data)
            ->additional(['success' => true]);
    }

    public function store(StorePelangganRequest $request)
    {
         $result = $this->pelangganService->createPelanggan($request->validated());
         return (new PelangganResource($result))
               ->additional(['message' => 'Berhasil menambah pelanggan!'])
               ->response()
               ->setStatusCode(201);
    }

    public function update(UpdatePelangganRequest $request, $id)
    {
         $result = $this->pelangganService->updatePelanggan($id, $request->validated());
         return (new PelangganResource($result))
            ->additional(['message' => 'Berhasil mengupdate pelanggan!'])
            ->response()
            ->setStatusCode(200);
    }
}
