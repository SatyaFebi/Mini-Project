<?php

namespace App\Http\Controllers\Api\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Transaksi\ItemPenjualanService;
use App\Http\Resources\Transaksi\ItemPenjualanResource;

class ItemPenjualanController extends Controller
{
    public function __construct(
        protected ItemPenjualanService $itemPenjualanService
    ) {}

    public function index(Request $request)
    {
        $data = $this->itemPenjualanService->getItemPenjualan($request->all());

        return ItemPenjualanResource::collection($data)
            ->additional(['message' => 'Berhasil mengambil data item penjualan']);
    }
}
