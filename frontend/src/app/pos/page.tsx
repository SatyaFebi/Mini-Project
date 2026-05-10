'use client';

import { useState, useMemo } from 'react';
import Link from 'next/link';
import { useBarangAll } from '@/hooks/use-barang';
import { usePelangganAll } from '@/hooks/use-pelanggan';
import { useCreatePenjualan } from '@/hooks/use-penjualan';
import { useCart, CartProvider, CartItem } from '@/lib/cart-context';
import { Barang, Pelanggan } from '@/lib/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter } from '@/components/ui/dialog';
import { Skeleton } from '@/components/ui/skeleton';
import { toast } from 'sonner';
import { Minus, Plus, ShoppingCart, Trash2, Search, X, CheckCircle2, Package, ArrowLeft } from 'lucide-react';

// ─── Format Currency ──────────────────────────
const fmt = (n: number) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(n);

// ─── Main POS Page (wrapped with CartProvider) ─
export default function POSPage() {
  return (
    <CartProvider>
      <POSContent />
    </CartProvider>
  );
}

// ─── POS Content ──────────────────────────────
function POSContent() {
  const { items, addItem, removeItem, updateQty, clearCart, totalItems, totalPrice } = useCart();
  const { data: barangData, isLoading: loadingBarang } = useBarangAll();
  const { data: pelangganData, isLoading: loadingPelanggan } = usePelangganAll();
  const createPenjualan = useCreatePenjualan();

  const [search, setSearch] = useState('');
  const [pelangganSearch, setPelangganSearch] = useState('');
  const [selectedPelanggan, setSelectedPelanggan] = useState<Pelanggan | null>(null);
  const [showPelangganDropdown, setShowPelangganDropdown] = useState(false);
  const [successData, setSuccessData] = useState<any>(null);
  const [showSuccess, setShowSuccess] = useState(false);
  const [isCartVisible, setIsCartVisible] = useState(false);

  // Filter barang client-side
  const barangList: Barang[] = useMemo(() => {
    const list = barangData?.data || [];
    if (!search) return list;
    const q = search.toLowerCase();
    return list.filter((b: Barang) =>
      b.nama.toLowerCase().includes(q) || b.kode.toLowerCase().includes(q) || b.kategori.toLowerCase().includes(q)
    );
  }, [barangData, search]);

  // Filter pelanggan client-side
  const pelangganList: Pelanggan[] = useMemo(() => {
    const list = pelangganData?.data || [];
    if (!pelangganSearch) return list;
    const q = pelangganSearch.toLowerCase();
    return list.filter((p: Pelanggan) =>
      p.nama.toLowerCase().includes(q) || p.id_pelanggan.toLowerCase().includes(q)
    );
  }, [pelangganData, pelangganSearch]);

  const today = new Date().toISOString().split('T')[0];

  const handleCheckout = async () => {
    if (!selectedPelanggan) {
      toast.error('Pilih pelanggan terlebih dahulu.');
      return;
    }
    if (items.length === 0) {
      toast.error('Cart masih kosong.');
      return;
    }

    try {
      const payload = {
        tgl: today,
        kode_pelanggan: selectedPelanggan.id_pelanggan,
        items: items.map(i => ({ kode_barang: i.kode_barang, qty: i.qty })),
      };
      const res = await createPenjualan.mutateAsync(payload);
      setSuccessData(res.data);
      setShowSuccess(true);
      clearCart();
      setSelectedPelanggan(null);
      setPelangganSearch('');
    } catch (error: any) {
      toast.error(error.response?.data?.message || 'Gagal memproses checkout.');
    }
  };

  return (
    <div className={`flex h-screen p-6 ${isCartVisible ? 'gap-6' : 'gap-0'} transition-all duration-300`}>
      {/* ═══ LEFT: Katalog ═══ */}
      <div className="flex flex-1 flex-col min-w-0 px-5">
        <div className="mb-4 flex items-center justify-between">
          <div className="flex items-center gap-3">
            {/* <Link href="/pelanggan">
              <Button variant="outline" size="icon" className="h-9 w-9">
                <ArrowLeft className="h-4 w-4" />
              </Button>
            </Link> */}
            <div>
              <h2 className="text-2xl font-bold">Point of Sale</h2>
              <p className="text-sm text-muted-foreground">{new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</p>
            </div>
          </div>
          <Button variant="outline" onClick={() => setIsCartVisible(!isCartVisible)} className="gap-2">
            <ShoppingCart className="h-4 w-4" />
            {isCartVisible ? 'Sembunyikan Cart' : 'Tampilkan Cart'}
            {!isCartVisible && totalItems > 0 && (
              <span className="bg-primary text-primary-foreground text-xs font-bold rounded-full h-5 min-w-5 px-1.5 flex items-center justify-center">
                {totalItems}
              </span>
            )}
          </Button>
        </div>

        {/* Search */}
        <div className="relative mb-4">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input
            placeholder="Cari barang (nama, kode, kategori)..."
            value={search}
            onChange={e => setSearch(e.target.value)}
            className="pl-10"
          />
          {search && (
            <button onClick={() => setSearch('')} className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground">
              <X className="h-4 w-4" />
            </button>
          )}
        </div>

        {/* Grid Katalog */}
        <div className="flex-1 overflow-y-auto pr-1">
          {loadingBarang ? (
            <div className="grid grid-cols-2 lg:grid-cols-3 gap-3">
              {Array.from({ length: 6 }).map((_, i) => (
                <div key={i} className="rounded-xl border p-4 space-y-3">
                  <Skeleton className="h-4 w-3/4" />
                  <Skeleton className="h-3 w-1/2" />
                  <Skeleton className="h-8 w-full" />
                </div>
              ))}
            </div>
          ) : barangList.length === 0 ? (
            <div className="flex flex-col items-center justify-center h-48 text-muted-foreground">
              <Package className="h-12 w-12 mb-2 opacity-50" />
              <p>Tidak ada barang ditemukan.</p>
            </div>
          ) : (
            <div className="grid grid-cols-2 lg:grid-cols-3 gap-3">
              {barangList.map((barang: Barang) => {
                const inCart = items.find(i => i.kode_barang === barang.kode);
                return (
                  <div
                    key={barang.id}
                    className={`group relative rounded-xl border p-4 transition-all hover:shadow-md hover:border-primary/50 ${inCart ? 'border-primary bg-primary/5' : ''}`}
                  >
                    <div className="flex items-start justify-between mb-1">
                      <h3 className="font-semibold text-sm leading-tight">{barang.nama}</h3>
                      {inCart && (
                        <span className="shrink-0 ml-2 bg-primary text-primary-foreground text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                          {inCart.qty}
                        </span>
                      )}
                    </div>
                    <span className="inline-block text-xs px-2 py-0.5 rounded-full bg-muted text-muted-foreground mb-2">
                      {barang.kategori}
                    </span>
                    <p className="text-sm font-bold text-primary mb-3">{fmt(barang.harga)}</p>
                    <Button size="sm" className="w-full" variant={inCart ? 'secondary' : 'default'} onClick={() => { addItem(barang); setIsCartVisible(true); }}>
                      <Plus className="h-4 w-4 mr-1" />
                      {inCart ? 'Tambah Lagi' : 'Add to Cart'}
                    </Button>
                  </div>
                );
              })}
            </div>
          )}
        </div>
      </div>

      {/* ═══ RIGHT: Cart Sidebar ═══ */}
      <div className={`${isCartVisible ? 'w-[380px] opacity-100' : 'w-0 opacity-0 border-0 overflow-hidden'} transition-all duration-300 shrink-0 flex flex-col rounded-xl border bg-card`}>
        {/* Cart Header */}
        <div className="p-4 border-b">
          <div className="flex items-center gap-2">
            <ShoppingCart className="h-5 w-5" />
            <h3 className="font-semibold text-lg">Keranjang</h3>
            {totalItems > 0 && (
              <span className="bg-primary text-primary-foreground text-xs font-bold rounded-full h-5 min-w-5 px-1.5 flex items-center justify-center">
                {totalItems}
              </span>
            )}
          </div>
        </div>

        {/* Cart Items */}
        <div className="flex-1 overflow-y-auto p-4 space-y-3">
          {items.length === 0 ? (
            <div className="flex flex-col items-center justify-center h-full text-muted-foreground">
              <ShoppingCart className="h-12 w-12 mb-2 opacity-30" />
              <p className="text-sm">Cart kosong</p>
            </div>
          ) : (
            items.map((item: CartItem) => (
              <div key={item.kode_barang} className="flex items-start gap-3 rounded-lg border p-3">
                <div className="flex-1 min-w-0">
                  <p className="font-medium text-sm truncate">{item.nama}</p>
                  <p className="text-xs text-muted-foreground">{fmt(item.harga)} × {item.qty}</p>
                  <p className="text-sm font-semibold text-primary mt-1">{fmt(item.harga * item.qty)}</p>
                </div>
                <div className="flex items-center gap-1">
                  <Button variant="outline" size="icon" className="h-7 w-7" onClick={() => updateQty(item.kode_barang, item.qty - 1)}>
                    <Minus className="h-3 w-3" />
                  </Button>
                  <span className="w-8 text-center text-sm font-medium">{item.qty}</span>
                  <Button variant="outline" size="icon" className="h-7 w-7" onClick={() => updateQty(item.kode_barang, item.qty + 1)}>
                    <Plus className="h-3 w-3" />
                  </Button>
                  <Button variant="ghost" size="icon" className="h-7 w-7 text-destructive hover:text-destructive" onClick={() => removeItem(item.kode_barang)}>
                    <Trash2 className="h-3 w-3" />
                  </Button>
                </div>
              </div>
            ))
          )}
        </div>

        {/* Checkout Section */}
        <div className="border-t p-4 space-y-3">
          {/* Pelanggan Selector */}
          <div className="relative">
            <label className="text-sm font-medium mb-1 block">Pelanggan</label>
            <Input
              placeholder="Cari pelanggan..."
              value={selectedPelanggan ? `${selectedPelanggan.id_pelanggan} — ${selectedPelanggan.nama}` : pelangganSearch}
              onChange={e => {
                setPelangganSearch(e.target.value);
                setSelectedPelanggan(null);
                setShowPelangganDropdown(true);
              }}
              onFocus={() => setShowPelangganDropdown(true)}
            />
            {selectedPelanggan && (
              <button
                onClick={() => { setSelectedPelanggan(null); setPelangganSearch(''); }}
                className="absolute right-3 top-8 text-muted-foreground hover:text-foreground"
              >
                <X className="h-4 w-4" />
              </button>
            )}
            {showPelangganDropdown && !selectedPelanggan && (
              <div className="absolute z-50 mt-1 w-full max-h-40 overflow-y-auto rounded-md border bg-popover shadow-md">
                {loadingPelanggan ? (
                  <div className="p-3 text-sm text-muted-foreground">Memuat...</div>
                ) : pelangganList.length === 0 ? (
                  <div className="p-3 text-sm text-muted-foreground">Tidak ditemukan.</div>
                ) : (
                  pelangganList.map((p: Pelanggan) => (
                    <button
                      key={p.id}
                      className="w-full text-left px-3 py-2 text-sm hover:bg-accent transition-colors"
                      onClick={() => {
                        setSelectedPelanggan(p);
                        setPelangganSearch('');
                        setShowPelangganDropdown(false);
                      }}
                    >
                      <span className="font-medium">{p.id_pelanggan}</span>
                      <span className="text-muted-foreground"> — {p.nama}</span>
                    </button>
                  ))
                )}
              </div>
            )}
          </div>

          {/* Total */}
          <div className="flex items-center justify-between text-lg font-bold pt-2 border-t">
            <span>Total</span>
            <span className="text-primary">{fmt(totalPrice)}</span>
          </div>

          {/* Checkout Button */}
          <Button
            className="w-full"
            size="lg"
            disabled={items.length === 0 || !selectedPelanggan || createPenjualan.isPending}
            onClick={handleCheckout}
          >
            {createPenjualan.isPending ? 'Memproses...' : 'Proses Checkout'}
          </Button>
        </div>
      </div>

      {/* ═══ Success Modal ═══ */}
      <Dialog open={showSuccess} onOpenChange={setShowSuccess}>
        <DialogContent className="sm:max-w-md">
          <DialogHeader>
            <div className="flex items-center gap-2">
              <CheckCircle2 className="h-6 w-6 text-green-500" />
              <DialogTitle>Order Berhasil!</DialogTitle>
            </div>
            <DialogDescription>Transaksi telah berhasil diproses.</DialogDescription>
          </DialogHeader>

          {successData && (
            <div className="space-y-3 py-2">
              <div className="grid grid-cols-2 gap-2 text-sm">
                <div>
                  <p className="text-muted-foreground">No. Nota</p>
                  <p className="font-semibold">{successData.id_nota}</p>
                </div>
                <div>
                  <p className="text-muted-foreground">Tanggal</p>
                  <p className="font-semibold">{successData.tgl}</p>
                </div>
                <div>
                  <p className="text-muted-foreground">Pelanggan</p>
                  <p className="font-semibold">{successData.pelanggan?.nama || successData.kode_pelanggan}</p>
                </div>
                <div>
                  <p className="text-muted-foreground">Total</p>
                  <p className="font-semibold text-primary">{fmt(successData.subtotal)}</p>
                </div>
              </div>

              {successData.items?.length > 0 && (
                <div className="border rounded-lg overflow-hidden">
                  <table className="w-full text-sm">
                    <thead>
                      <tr className="bg-muted/50">
                        <th className="text-left p-2 font-medium">Barang</th>
                        <th className="text-center p-2 font-medium">Qty</th>
                      </tr>
                    </thead>
                    <tbody>
                      {successData.items.map((item: any, idx: number) => (
                        <tr key={idx} className="border-t">
                          <td className="p-2">{item.barang?.nama || item.kode_barang}</td>
                          <td className="p-2 text-center">{item.qty}</td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              )}
            </div>
          )}

          <DialogFooter>
            <Button className="w-full" onClick={() => setShowSuccess(false)}>Tutup</Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  );
}
