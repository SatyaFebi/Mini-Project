'use client';

import { useState, useEffect } from 'react';
import { usePenjualan, useCreatePenjualan, useUpdatePenjualan, useDeletePenjualan } from '../../../hooks/use-penjualan';
import { usePelangganAll } from '../../../hooks/use-pelanggan';
import { useBarangAll } from '../../../hooks/use-barang';
import { Button } from '../../../components/ui/button';
import { Input } from '../../../components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '../../../components/ui/table';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '../../../components/ui/dialog';
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle } from '../../../components/ui/alert-dialog';
import { Label } from '../../../components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../../../components/ui/select';
import { Skeleton } from '../../../components/ui/skeleton';
import { toast } from 'sonner';
import { Penjualan, Pelanggan, Barang } from '../../../lib/types';
import { Plus, Trash } from 'lucide-react';

export default function PenjualanPage() {
  const [search, setSearch] = useState('');
  const [debouncedSearch, setDebouncedSearch] = useState('');
  const [page, setPage] = useState(1);
  const [isOpen, setIsOpen] = useState(false);
  const [isDeleteOpen, setIsDeleteOpen] = useState(false);
  const [selectedPenjualan, setSelectedPenjualan] = useState<Penjualan | null>(null);
  
  const [formData, setFormData] = useState({
    tgl: new Date().toISOString().split('T')[0],
    kode_pelanggan: '',
    items: [] as { kode_barang: string; qty: number }[]
  });

  useEffect(() => {
    const handler = setTimeout(() => {
      setDebouncedSearch(search);
      setPage(1);
    }, 300);
    return () => clearTimeout(handler);
  }, [search]);

  const { data, isLoading } = usePenjualan({ search: debouncedSearch, page });
  const { data: pelanggans } = usePelangganAll();
  const { data: barangs } = useBarangAll();
  
  const createMutation = useCreatePenjualan();
  const updateMutation = useUpdatePenjualan();
  const deleteMutation = useDeletePenjualan();

  const handleOpenDialog = (penjualan: Penjualan | null = null) => {
    if (penjualan) {
      setSelectedPenjualan(penjualan);
      setFormData({
        tgl: penjualan.tgl.split(' ')[0],
        kode_pelanggan: penjualan.kode_pelanggan,
        items: penjualan.items?.map(item => ({
          kode_barang: item.kode_barang,
          qty: item.qty
        })) || []
      });
    } else {
      setSelectedPenjualan(null);
      setFormData({
        tgl: new Date().toISOString().split('T')[0],
        kode_pelanggan: '',
        items: [{ kode_barang: '', qty: 1 }]
      });
    }
    setIsOpen(true);
  };

  const handleAddItem = () => {
    setFormData({
      ...formData,
      items: [...formData.items, { kode_barang: '', qty: 1 }]
    });
  };

  const handleRemoveItem = (index: number) => {
    const newItems = [...formData.items];
    newItems.splice(index, 1);
    setFormData({ ...formData, items: newItems });
  };

  const handleItemChange = (index: number, field: string, value: any) => {
    const newItems = [...formData.items];
    newItems[index] = { ...newItems[index], [field]: value };
    setFormData({ ...formData, items: newItems });
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (formData.items.length === 0) {
      toast.error('Minimal harus ada 1 item.');
      return;
    }
    if (formData.items.some(item => !item.kode_barang)) {
      toast.error('Semua item harus memiliki barang.');
      return;
    }
    
    try {
      if (selectedPenjualan) {
        await updateMutation.mutateAsync({ id: selectedPenjualan.id, data: formData });
        toast.success('Penjualan berhasil diupdate!');
      } else {
        await createMutation.mutateAsync(formData);
        toast.success('Penjualan berhasil ditambahkan!');
      }
      setIsOpen(false);
    } catch (error: any) {
      toast.error(error.response?.data?.message || 'Terjadi kesalahan.');
    }
  };

  const handleDelete = async () => {
    if (!selectedPenjualan) return;
    try {
      await deleteMutation.mutateAsync(selectedPenjualan.id);
      toast.success('Penjualan berhasil dihapus!');
      setIsDeleteOpen(false);
    } catch (error: any) {
      toast.error(error.response?.data?.message || 'Gagal menghapus penjualan.');
    }
  };

  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount);
  };

  const getBarangPrice = (kode: string) => {
    const barang = barangs?.data?.find((b: Barang) => b.kode === kode);
    return barang?.harga || 0;
  };

  const calculateSubtotal = () => {
    return formData.items.reduce((acc, item) => {
      return acc + (getBarangPrice(item.kode_barang) * item.qty);
    }, 0);
  };

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <h2 className="text-2xl font-bold">Data Penjualan</h2>
        <Button onClick={() => handleOpenDialog()}>Tambah Penjualan</Button>
      </div>

      <div className="flex items-center gap-4">
        <Input
          placeholder="Cari nota atau pelanggan..."
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          className="max-w-sm"
        />
      </div>

      <div className="border rounded-md">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>No. Nota</TableHead>
              <TableHead>Tanggal</TableHead>
              <TableHead>Pelanggan</TableHead>
              <TableHead>Subtotal</TableHead>
              <TableHead className="text-right">Aksi</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {isLoading ? (
              Array.from({ length: 5 }).map((_, index) => (
                <TableRow key={index}>
                  <TableCell><Skeleton className="h-4 w-28" /></TableCell>
                  <TableCell><Skeleton className="h-4 w-20" /></TableCell>
                  <TableCell><Skeleton className="h-4 w-32" /></TableCell>
                  <TableCell><Skeleton className="h-4 w-24" /></TableCell>
                  <TableCell className="text-right"><Skeleton className="h-4 w-20 ml-auto" /></TableCell>
                </TableRow>
              ))
            ) : data?.data?.length === 0 ? (
              <TableRow>
                <TableCell colSpan={5} className="text-center py-4 text-muted-foreground">
                  Data tidak ditemukan.
                </TableCell>
              </TableRow>
            ) : (
              data?.data?.map((item: Penjualan) => (
                <TableRow key={item.id}>
                  <TableCell>{item.id_nota}</TableCell>
                  <TableCell>{new Date(item.tgl).toLocaleDateString('id-ID')}</TableCell>
                  <TableCell>{item.pelanggan?.nama || item.kode_pelanggan}</TableCell>
                  <TableCell>{formatCurrency(item.subtotal)}</TableCell>
                  <TableCell className="text-right space-x-2">
                    <Button variant="outline" size="sm" onClick={() => handleOpenDialog(item)}>Edit</Button>
                    <Button variant="destructive" size="sm" onClick={() => { setSelectedPenjualan(item); setIsDeleteOpen(true); }}>Hapus</Button>
                  </TableCell>
                </TableRow>
              ))
            )}
          </TableBody>
        </Table>
      </div>

      <Dialog open={isOpen} onOpenChange={setIsOpen}>
        <DialogContent className="max-w-4xl max-h-[90vh] overflow-y-auto">
          <DialogHeader>
            <DialogTitle>{selectedPenjualan ? 'Edit Penjualan' : 'Tambah Penjualan'}</DialogTitle>
            <DialogDescription>
              Isi formulir di bawah ini untuk {selectedPenjualan ? 'mengupdate' : 'menambah'} data penjualan.
            </DialogDescription>
          </DialogHeader>
          <form onSubmit={handleSubmit}>
            <div className="space-y-4 py-4">
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="tgl">Tanggal</Label>
                  <Input
                    id="tgl"
                    type="date"
                    value={formData.tgl}
                    onChange={(e) => setFormData({ ...formData, tgl: e.target.value })}
                    required
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="kode_pelanggan">Pelanggan</Label>
                  <Select
                    value={formData.kode_pelanggan}
                    onValueChange={(value) => setFormData({ ...formData, kode_pelanggan: value })}
                  >
                    <SelectTrigger>
                      <SelectValue placeholder="Pilih Pelanggan" />
                    </SelectTrigger>
                    <SelectContent>
                      {pelanggans?.data?.map((p: Pelanggan) => (
                        <SelectItem key={p.id_pelanggan} value={p.id_pelanggan}>
                          {p.nama} ({p.id_pelanggan})
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
              </div>

              <div className="space-y-2">
                <div className="flex items-center justify-between">
                  <Label>Items</Label>
                  <Button type="button" variant="outline" size="sm" onClick={handleAddItem}>
                    <Plus className="mr-2 h-4 w-4" /> Tambah Item
                  </Button>
                </div>

                <div className="border rounded-md p-2 space-y-2">
                  {formData.items.map((item, index) => (
                    <div key={index} className="grid grid-cols-12 gap-2 items-center">
                      <div className="col-span-6">
                        <Select
                          value={item.kode_barang}
                          onValueChange={(value) => handleItemChange(index, 'kode_barang', value)}
                        >
                          <SelectTrigger>
                            <SelectValue placeholder="Pilih Barang" />
                          </SelectTrigger>
                          <SelectContent>
                            {barangs?.data?.map((b: Barang) => (
                              <SelectItem key={b.kode} value={b.kode}>
                                {b.nama} ({formatCurrency(b.harga)})
                              </SelectItem>
                            ))}
                          </SelectContent>
                        </Select>
                      </div>
                      <div className="col-span-3">
                        <Input
                          type="number"
                          placeholder="Qty"
                          value={item.qty}
                          onChange={(e) => handleItemChange(index, 'qty', parseInt(e.target.value) || 1)}
                          min={1}
                          required
                        />
                      </div>
                      <div className="col-span-2 text-sm font-medium">
                        {formatCurrency(getBarangPrice(item.kode_barang) * item.qty)}
                      </div>
                      <div className="col-span-1 text-right">
                        <Button
                          type="button"
                          variant="ghost"
                          size="sm"
                          onClick={() => handleRemoveItem(index)}
                          disabled={formData.items.length === 1}
                        >
                          <Trash className="h-4 w-4 text-red-600" />
                        </Button>
                      </div>
                    </div>
                  ))}
                </div>
              </div>

              <div className="flex justify-end text-lg font-bold">
                Total: {formatCurrency(calculateSubtotal())}
              </div>
            </div>
            <DialogFooter>
              <Button type="button" variant="outline" onClick={() => setIsOpen(false)}>Batal</Button>
              <Button type="submit" disabled={createMutation.isPending || updateMutation.isPending}>
                {selectedPenjualan ? 'Update' : 'Simpan'}
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      <AlertDialog open={isDeleteOpen} onOpenChange={setIsDeleteOpen}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Apakah Anda yakin?</AlertDialogTitle>
            <AlertDialogDescription>
              Tindakan ini tidak dapat dibatalkan. Ini akan menghapus data penjualan dengan nota{' '}
              <span className="font-semibold">{selectedPenjualan?.id_nota}</span>.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Batal</AlertDialogCancel>
            <AlertDialogAction onClick={handleDelete} className="bg-destructive text-destructive-foreground hover:bg-destructive/90">
              Hapus
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </div>
  );
}
