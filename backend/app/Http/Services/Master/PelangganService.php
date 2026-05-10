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
        $mappedData = array_combine(
            array_map('strtoupper', array_keys($data)),
            array_values($data)
        );
        return Pelanggan::create($mappedData);
    }

    public function updatePelanggan($id, array $data)
    {
        $result = Pelanggan::findOrFail($id);

        $mappedData = array_combine(
            array_map('strtoupper', array_keys($data)),
            array_values($data)
        );
        $result->update($mappedData);

        return $result;
    }

    public function getAll()
    {
        return Pelanggan::all();
    }

    public function findById($id)
    {
        return Pelanggan::findOrFail($id);
    }

    public function deletePelanggan($id)
    {
        $result = Pelanggan::findOrFail($id);
        return $result->delete();
    }
}