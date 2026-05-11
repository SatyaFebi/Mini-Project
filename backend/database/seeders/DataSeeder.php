<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pelanggans = [
            ['ID_PELANGGAN' => 'PELANGGAN_1', 'NAMA' => 'ANDI', 'DOMISILI' => 'JAK-UT', 'JENIS_KELAMIN' => 'PRIA'],
            ['ID_PELANGGAN' => 'PELANGGAN_2', 'NAMA' => 'BUDI', 'DOMISILI' => 'JAK-BAR', 'JENIS_KELAMIN' => 'PRIA'],
            ['ID_PELANGGAN' => 'PELANGGAN_3', 'NAMA' => 'JOHAN', 'DOMISILI' => 'JAK-SEL', 'JENIS_KELAMIN' => 'PRIA'],
            ['ID_PELANGGAN' => 'PELANGGAN_4', 'NAMA' => 'SINTHA', 'DOMISILI' => 'JAK-TIM', 'JENIS_KELAMIN' => 'WANITA'],
            ['ID_PELANGGAN' => 'PELANGGAN_5', 'NAMA' => 'ANTO', 'DOMISILI' => 'JAK-UT', 'JENIS_KELAMIN' => 'PRIA'],
            ['ID_PELANGGAN' => 'PELANGGAN_6', 'NAMA' => 'BUJANG', 'DOMISILI' => 'JAK-BAR', 'JENIS_KELAMIN' => 'PRIA'],
            ['ID_PELANGGAN' => 'PELANGGAN_7', 'NAMA' => 'JOWAN', 'DOMISILI' => 'JAK-SEL', 'JENIS_KELAMIN' => 'PRIA'],
            ['ID_PELANGGAN' => 'PELANGGAN_8', 'NAMA' => 'SINTIA', 'DOMISILI' => 'JAK-TIM', 'JENIS_KELAMIN' => 'WANITA'],
            ['ID_PELANGGAN' => 'PELANGGAN_9', 'NAMA' => 'BUTET', 'DOMISILI' => 'JAK-BAR', 'JENIS_KELAMIN' => 'WANITA'],
            ['ID_PELANGGAN' => 'PELANGGAN_10', 'NAMA' => 'JONNY', 'DOMISILI' => 'JAK-SEL', 'JENIS_KELAMIN' => 'WANITA'],
        ];

        $barangs = [
            ['KODE' => 'BRG_1', 'NAMA' => 'PEN', 'KATEGORI' => 'ATK', 'HARGA' => 15000],
            ['KODE' => 'BRG_2', 'NAMA' => 'PENSIL', 'KATEGORI' => 'ATK', 'HARGA' => 10000],
            ['KODE' => 'BRG_3', 'NAMA' => 'PAYUNG', 'KATEGORI' => 'RT', 'HARGA' => 70000],
            ['KODE' => 'BRG_4', 'NAMA' => 'PANCI', 'KATEGORI' => 'MASAK', 'HARGA' => 110000],
            ['KODE' => 'BRG_5', 'NAMA' => 'SAPU', 'KATEGORI' => 'RT', 'HARGA' => 40000],
            ['KODE' => 'BRG_6', 'NAMA' => 'KIPAS', 'KATEGORI' => 'ELEKTRONIK', 'HARGA' => 200000],
            ['KODE' => 'BRG_7', 'NAMA' => 'KUALI', 'KATEGORI' => 'MASAK', 'HARGA' => 120000],
            ['KODE' => 'BRG_8', 'NAMA' => 'SIKAT', 'KATEGORI' => 'RT', 'HARGA' => 30000],
            ['KODE' => 'BRG_9', 'NAMA' => 'GELAS', 'KATEGORI' => 'RT', 'HARGA' => 25000],
            ['KODE' => 'BRG_10', 'NAMA' => 'PIRING', 'KATEGORI' => 'RT', 'HARGA' => 35000],
        ];

        $penjualans = [
            ['ID_NOTA' => 'NOTA_1', 'TGL' => '2018-01-01', 'KODE_PELANGGAN' => 'PELANGGAN_1', 'SUBTOTAL' => 50000],
            ['ID_NOTA' => 'NOTA_2', 'TGL' => '2018-01-01', 'KODE_PELANGGAN' => 'PELANGGAN_2', 'SUBTOTAL' => 200000],
            ['ID_NOTA' => 'NOTA_3', 'TGL' => '2018-01-01', 'KODE_PELANGGAN' => 'PELANGGAN_3', 'SUBTOTAL' => 430000],
            ['ID_NOTA' => 'NOTA_4', 'TGL' => '2018-01-02', 'KODE_PELANGGAN' => 'PELANGGAN_7', 'SUBTOTAL' => 120000],
            ['ID_NOTA' => 'NOTA_5', 'TGL' => '2018-01-02', 'KODE_PELANGGAN' => 'PELANGGAN_4', 'SUBTOTAL' => 70000],
            ['ID_NOTA' => 'NOTA_6', 'TGL' => '2018-01-03', 'KODE_PELANGGAN' => 'PELANGGAN_8', 'SUBTOTAL' => 230000],
            ['ID_NOTA' => 'NOTA_7', 'TGL' => '2018-01-03', 'KODE_PELANGGAN' => 'PELANGGAN_9', 'SUBTOTAL' => 390000],
            ['ID_NOTA' => 'NOTA_8', 'TGL' => '2018-01-03', 'KODE_PELANGGAN' => 'PELANGGAN_5', 'SUBTOTAL' => 65000],
            ['ID_NOTA' => 'NOTA_9', 'TGL' => '2018-01-04', 'KODE_PELANGGAN' => 'PELANGGAN_2', 'SUBTOTAL' => 40000],
            ['ID_NOTA' => 'NOTA_10', 'TGL' => '2018-01-04', 'KODE_PELANGGAN' => 'PELANGGAN_1', 'SUBTOTAL' => 400000],
        ];

        $itemPenjualans = [
            ['NOTA' => 'NOTA_1', 'KODE_BARANG' => 'BRG_1', 'Qty' => 2],
            ['NOTA' => 'NOTA_1', 'KODE_BARANG' => 'BRG_2', 'Qty' => 2],
            ['NOTA' => 'NOTA_2', 'KODE_BARANG' => 'BRG_6', 'Qty' => 1],
            ['NOTA' => 'NOTA_3', 'KODE_BARANG' => 'BRG_4', 'Qty' => 1],
            ['NOTA' => 'NOTA_3', 'KODE_BARANG' => 'BRG_7', 'Qty' => 1],
            ['NOTA' => 'NOTA_3', 'KODE_BARANG' => 'BRG_6', 'Qty' => 1],
            ['NOTA' => 'NOTA_4', 'KODE_BARANG' => 'BRG_9', 'Qty' => 2],
            ['NOTA' => 'NOTA_4', 'KODE_BARANG' => 'BRG_10', 'Qty' => 2],
            ['NOTA' => 'NOTA_5', 'KODE_BARANG' => 'BRG_3', 'Qty' => 1],
            ['NOTA' => 'NOTA_6', 'KODE_BARANG' => 'BRG_7', 'Qty' => 1],
            ['NOTA' => 'NOTA_6', 'KODE_BARANG' => 'BRG_5', 'Qty' => 1],
            ['NOTA' => 'NOTA_6', 'KODE_BARANG' => 'BRG_3', 'Qty' => 1],
            ['NOTA' => 'NOTA_7', 'KODE_BARANG' => 'BRG_5', 'Qty' => 1],
            ['NOTA' => 'NOTA_7', 'KODE_BARANG' => 'BRG_6', 'Qty' => 1],
            ['NOTA' => 'NOTA_7', 'KODE_BARANG' => 'BRG_7', 'Qty' => 1],
            ['NOTA' => 'NOTA_7', 'KODE_BARANG' => 'BRG_8', 'Qty' => 1],
            ['NOTA' => 'NOTA_8', 'KODE_BARANG' => 'BRG_5', 'Qty' => 1],
            ['NOTA' => 'NOTA_8', 'KODE_BARANG' => 'BRG_9', 'Qty' => 1],
            ['NOTA' => 'NOTA_9', 'KODE_BARANG' => 'BRG_5', 'Qty' => 1],
            ['NOTA' => 'NOTA_10', 'KODE_BARANG' => 'BRG_5', 'Qty' => 10],
        ];

        DB::table('pelanggans')->insert($pelanggans);
        DB::table('barangs')->insert($barangs);
        DB::table('penjualans')->insert($penjualans);
        DB::table('item_penjualans')->insert($itemPenjualans);
    }
}
