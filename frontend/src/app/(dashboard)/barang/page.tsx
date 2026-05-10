'use client';

import { useState, useEffect } from 'react';
import { useBarang, useCreateBarang, useUpdateBarang, useDeleteBarang } from '../../../hooks/use-barang';
import { Button } from '../../../components/ui/button';
import { Input } from '../../../components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '../../../components/ui/table';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '../../../components/ui/dialog';
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogTrigger } from '../../../components/ui/alert-dialog';
import { Label } from '../../../components/ui/label';
import { Skeleton } from '../../../components/ui/skeleton';
import { toast } from 'sonner';
import { Barang } from '../../../lib/types';

export default function BarangPage() {
  const [search, setSearch] = useState('');
  const [debouncedSearch, setDebouncedSearch] = useState('');
  const [page, setPage] = useState(1);
  const [isOpen, setIsOpen] = useState(false);
  const [isDeleteOpen, setIsDeleteOpen] = useState(false);
  const [selectedBarang, setSelectedBarang] = useState<Barang | null>(null);
  
  const [formData, setFormData] = useState({
    kode: '',
    nama: '',
    kategori: '',
    harga: 0
  });

  useEffect(() => {
    const handler = setTimeout(() => {
      setDebouncedSearch(search);
      setPage(1);
    }, 300);
    return () => clearTimeout(handler);
  }, [search]);

  const { data, isLoading, isError } = useBarang({ search: debouncedSearch, page });
  const createMutation = useCreateBarang();
  const updateMutation = useUpdateBarang();
  const deleteMutation = useDeleteBarang();

  const handleOpenDialog = (barang: Barang | null = null) => {
    if (barang) {
      setSelectedBarang(barang);
      setFormData({
        kode: barang.kode,
        nama: barang.nama,
        kategori: barang.kategori,
        harga: barang.harga
      });
    } else {
      setSelectedBarang(null);
      setFormData({ kode: '', nama: '', kategori: '', harga: 0 });
    }
    setIsOpen(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      if (selectedBarang) {
        await updateMutation.mutateAsync({ id: selectedBarang.id, data: formData });
        toast.success('Barang berhasil diupdate!');
      } else {
        await createMutation.mutateAsync(formData);
        toast.success('Barang berhasil ditambahkan!');
      }
      setIsOpen(false);
    } catch (error: any) {
      toast.error(error.response?.data?.message || 'Terjadi kesalahan.');
    }
  };

  const handleDelete = async () => {
    if (!selectedBarang) return;
    try {
      await deleteMutation.mutateAsync(selectedBarang.id);
      toast.success('Barang berhasil dihapus!');
      setIsDeleteOpen(false);
    } catch (error: any) {
      toast.error(error.response?.data?.message || 'Gagal menghapus barang.');
    }
  };

  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(amount);
  };

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <h2 className="text-2xl font-bold">Data Barang</h2>
        <Button onClick={() => handleOpenDialog()}>Tambah Barang</Button>
      </div>

      <div className="flex items-center gap-4">
        <Input
          placeholder="Cari nama atau kode..."
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          className="max-w-sm"
        />
      </div>

      <div className="border rounded-md">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Kode</TableHead>
              <TableHead>Nama</TableHead>
              <TableHead>Kategori</TableHead>
              <TableHead>Harga</TableHead>
              <TableHead className="text-right">Aksi</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {isLoading ? (
              Array.from({ length: 5 }).map((_, index) => (
                <TableRow key={index}>
                  <TableCell><Skeleton className="h-4 w-20" /></TableCell>
                  <TableCell><Skeleton className="h-4 w-32" /></TableCell>
                  <TableCell><Skeleton className="h-4 w-24" /></TableCell>
                  <TableCell><Skeleton className="h-4 w-16" /></TableCell>
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
              data?.data?.map((item: Barang) => (
                <TableRow key={item.id}>
                  <TableCell>{item.kode}</TableCell>
                  <TableCell>{item.nama}</TableCell>
                  <TableCell>{item.kategori}</TableCell>
                  <TableCell>{formatCurrency(item.harga)}</TableCell>
                  <TableCell className="text-right space-x-2">
                    <Button variant="outline" size="sm" onClick={() => handleOpenDialog(item)}>Edit</Button>
                    <Button variant="destructive" size="sm" onClick={() => { setSelectedBarang(item); setIsDeleteOpen(true); }}>Hapus</Button>
                  </TableCell>
                </TableRow>
              ))
            )}
          </TableBody>
        </Table>
      </div>

      {data?.meta && (
        <div className="flex items-center justify-between">
          <div className="text-sm text-muted-foreground">
            Menampilkan {data.meta.from} - {data.meta.to} dari {data.meta.total} data
          </div>
          <div className="flex items-center gap-2">
            <Button
              variant="outline"
              size="sm"
              disabled={page === 1}
              onClick={() => setPage(page - 1)}
            >
              Sebelumnya
            </Button>
            <Button
              variant="outline"
              size="sm"
              disabled={page === data.meta.last_page}
              onClick={() => setPage(page + 1)}
            >
              Selanjutnya
            </Button>
          </div>
        </div>
      )}

      <Dialog open={isOpen} onOpenChange={setIsOpen}>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>{selectedBarang ? 'Edit Barang' : 'Tambah Barang'}</DialogTitle>
            <DialogDescription>
              Isi formulir di bawah ini untuk {selectedBarang ? 'mengupdate' : 'menambah'} data barang.
            </DialogDescription>
          </DialogHeader>
          <form onSubmit={handleSubmit}>
            <div className="space-y-4 py-4">
              <div className="space-y-2">
                <Label htmlFor="kode">Kode Barang</Label>
                <Input
                  id="kode"
                  value={formData.kode}
                  onChange={(e) => setFormData({ ...formData, kode: e.target.value })}
                  required
                  disabled={!!selectedBarang}
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="nama">Nama Barang</Label>
                <Input
                  id="nama"
                  value={formData.nama}
                  onChange={(e) => setFormData({ ...formData, nama: e.target.value })}
                  required
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="kategori">Kategori</Label>
                <Input
                  id="kategori"
                  value={formData.kategori}
                  onChange={(e) => setFormData({ ...formData, kategori: e.target.value })}
                  required
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="harga">Harga</Label>
                <Input
                  id="harga"
                  type="number"
                  value={formData.harga}
                  onChange={(e) => setFormData({ ...formData, harga: parseInt(e.target.value) || 0 })}
                  required
                />
              </div>
            </div>
            <DialogFooter>
              <Button type="button" variant="outline" onClick={() => setIsOpen(false)}>Batal</Button>
              <Button type="submit" disabled={createMutation.isPending || updateMutation.isPending}>
                {selectedBarang ? 'Update' : 'Simpan'}
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
              Tindakan ini tidak dapat dibatalkan. Ini akan menghapus data barang{' '}
              <span className="font-semibold">{selectedBarang?.nama}</span>.
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
