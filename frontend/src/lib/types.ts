export interface Pelanggan {
  id: number;
  id_pelanggan: string;
  nama: string;
  domisili: string;
  jenis_kelamin: 'PRIA' | 'WANITA';
}

export interface Barang {
  id: number;
  kode: string;
  nama: string;
  kategori: string;
  harga: number;
}

export interface Penjualan {
  id: number;
  id_nota: string;
  tgl: string;
  kode_pelanggan: string;
  subtotal: number;
  pelanggan?: Pelanggan;
  items?: ItemPenjualan[];
  created_at?: string;
}

export interface ItemPenjualan {
  id: number;
  nota: string;
  kode_barang: string;
  qty: number;
  barang?: Barang;
  subtotal_item?: number;
}
