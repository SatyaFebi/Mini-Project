<?php

namespace App\Http\Services\Master;

use App\Models\Pelanggan;

class PelangganService
{
    /**
     * Get paginated pelanggan with search.
     */
    public function getPelanggan(array $params)
    {
        $search = $params['search'] ?? null;
        $perPage = $params['per_page'] ?? 10;

        $query = Pelanggan::select('id', 'ID_PELANGGAN', 'NAMA', 'DOMISILI', 'JENIS_KELAMIN', 'created_at');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('NAMA', 'LIKE', '%' . $search . '%')
                  ->orWhere('ID_PELANGGAN', 'LIKE', '%' . $search . '%');
            });
        }

        return $query->paginate($perPage);
    }

    public function getAllPelanggan()
    {
        return Pelanggan::all();
    }

    public function createPelanggan(array $data)
    {
        return Pelanggan::create($data);
    }

    public function updatePelanggan($id, array $data)
    {
        $result = Pelanggan::findOrFail($id);

        $result->update($data);

        return $result;
    }
}