<?php

namespace App\Http\Services\Transaksi;

use App\Models\ItemPenjualan;

class ItemPenjualanService
{
    public function getItemPenjualan(array $params)
    {
        $perPage = $params['per_page'] ?? 10;
        $search = $params['search'] ?? null;
        $dateFrom = $params['date_from'] ?? null;
        $dateTo = $params['date_to'] ?? null;

        $query = ItemPenjualan::with(['penjualan.pelanggan', 'barang']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('NOTA', 'LIKE', "%{$search}%")
                  ->orWhere('KODE_BARANG', 'LIKE', "%{$search}%")
                  ->orWhereHas('barang', fn($b) => $b->where('NAMA', 'LIKE', "%{$search}%"))
                  ->orWhereHas('penjualan.pelanggan', fn($p) => $p->where('NAMA', 'LIKE', "%{$search}%"));
            });
        }

        if ($dateFrom) {
            $query->whereHas('penjualan', fn($q) => $q->where('TGL', '>=', $dateFrom));
        }

        if ($dateTo) {
            $query->whereHas('penjualan', fn($q) => $q->where('TGL', '<=', $dateTo));
        }

        return $query->latest('id')->paginate($perPage);
    }
}
