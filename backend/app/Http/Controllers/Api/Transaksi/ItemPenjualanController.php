<?php

namespace App\Http\Controllers\Api\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Transaksi\ItemPenjualanService;
use App\Http\Resources\Transaksi\ItemPenjualanResource;
use OpenApi\Attributes as OA;

class ItemPenjualanController extends Controller
{
    public function __construct(
        protected ItemPenjualanService $itemPenjualanService
    ) {}

    #[OA\Get(
        path: "/api/v1/transaksi/item-penjualan",
        summary: "Get list of item penjualan with pagination, search, and date filter",
        tags: ["Transaksi Item Penjualan"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "search", in: "query", description: "Search by nota, kode barang, nama barang, or nama pelanggan", required: false, schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "date_from", in: "query", description: "Filter from date (format: YYYY-MM-DD)", required: false, schema: new OA\Schema(type: "string", format: "date")),
            new OA\Parameter(name: "date_to", in: "query", description: "Filter to date (format: YYYY-MM-DD)", required: false, schema: new OA\Schema(type: "string", format: "date")),
            new OA\Parameter(name: "per_page", in: "query", description: "Items per page (default: 10)", required: false, schema: new OA\Schema(type: "integer")),
            new OA\Parameter(name: "page", in: "query", description: "Page number", required: false, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Berhasil mengambil data item penjualan",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "data", type: "array", items: new OA\Items(
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "nota", type: "string", example: "NOTA_1"),
                                new OA\Property(property: "kode_barang", type: "string", example: "BRG_1"),
                                new OA\Property(property: "qty", type: "integer", example: 2),
                                new OA\Property(property: "subtotal_item", type: "number", example: 30000),
                                new OA\Property(property: "barang", type: "object",
                                    properties: [
                                        new OA\Property(property: "kode", type: "string", example: "BRG_1"),
                                        new OA\Property(property: "nama", type: "string", example: "PEN"),
                                        new OA\Property(property: "harga", type: "number", example: 15000),
                                    ]
                                ),
                                new OA\Property(property: "penjualan", type: "object",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "id_nota", type: "string", example: "NOTA_1"),
                                        new OA\Property(property: "tgl", type: "string", example: "01 Januari 2018"),
                                        new OA\Property(property: "subtotal", type: "number", example: 50000),
                                        new OA\Property(property: "pelanggan", type: "object",
                                            properties: [
                                                new OA\Property(property: "id_pelanggan", type: "string", example: "PELANGGAN_1"),
                                                new OA\Property(property: "nama", type: "string", example: "ANDI"),
                                            ]
                                        ),
                                    ]
                                ),
                            ]
                        )),
                        new OA\Property(property: "message", type: "string", example: "Berhasil mengambil data item penjualan"),
                    ]
                )
            ),
            new OA\Response(response: 401, description: "Unauthenticated"),
        ]
    )]
    public function index(Request $request)
    {
        $data = $this->itemPenjualanService->getItemPenjualan($request->all());

        return ItemPenjualanResource::collection($data)
            ->additional(['message' => 'Berhasil mengambil data item penjualan']);
    }
}
