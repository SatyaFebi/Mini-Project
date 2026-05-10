'use client';

import { useState, useEffect } from 'react';
import { usePelanggan, useCreatePelanggan, useUpdatePelanggan, useDeletePelanggan } from '../../../hooks/use-pelanggan';
import { Button } from '../../../components/ui/button';
import { Input } from '../../../components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '../../../components/ui/table';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '../../../components/ui/dialog';
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogTrigger } from '../../../components/ui/alert-dialog';
import { Label } from '../../../components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../../../components/ui/select';
import { Skeleton } from '../../../components/ui/skeleton';
import { toast } from 'sonner';
import { Pelanggan } from '../../../lib/types';

export default function PelangganPage() {
  const [search, setSearch] = useState('');
  const [debouncedSearch, setDebouncedSearch] = useState('');
  const [page, setPage] = useState(1);
  const [isOpen, setIsOpen] = useState(false);
  const [isDeleteOpen, setIsDeleteOpen] = useState(false);
  const [selectedPelanggan, setSelectedPelanggan] = useState<Pelanggan | null>(null);
  
  const [formData, setFormData] = useState({
    nama: '',
    domisili: '',
    jenis_kelamin: 'PRIA' as 'PRIA' | 'WANITA'
  });

  useEffect(() => {
    const handler = setTimeout(() => {
      setDebouncedSearch(search);
      setPage(1);
    }, 300);
    return () => clearTimeout(handler);
  }, [search]);

  const { data, isLoading } = usePelanggan({ search: debouncedSearch, page });
  const createMutation = useCreatePelanggan();
  const updateMutation = useUpdatePelanggan();
  const deleteMutation = useDeletePelanggan();

  const handleOpenDialog = (pelanggan: Pelanggan | null = null) => {
    if (pelanggan) {
      setSelectedPelanggan(pelanggan);
      setFormData({
        nama: pelanggan.nama,
        domisili: pelanggan.domisili,
        jenis_kelamin: pelanggan.jenis_kelamin
      });
    } else {
      setSelectedPelanggan(null);
      setFormData({ nama: '', domisili: '', jenis_kelamin: 'PRIA' });
    }
    setIsOpen(true);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      if (selectedPelanggan) {
        await updateMutation.mutateAsync({ id: selectedPelanggan.id, data: formData });
        toast.success('Pelanggan berhasil diupdate!');
      } else {
        await createMutation.mutateAsync(formData);
        toast.success('Pelanggan berhasil ditambahkan!');
      }
      setIsOpen(false);
    } catch (error: any) {
      toast.error(error.response?.data?.message || 'Terjadi kesalahan.');
    }
  };

  const handleDelete = async () => {
    if (!selectedPelanggan) return;
    try {
      await deleteMutation.mutateAsync(selectedPelanggan.id);
      toast.success('Pelanggan berhasil dihapus!');
      setIsDeleteOpen(false);
    } catch (error: any) {
      toast.error(error.response?.data?.message || 'Gagal menghapus pelanggan.');
    }
  };

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <h2 className="text-2xl font-bold">Data Pelanggan</h2>
        <Button onClick={() => handleOpenDialog()}>Tambah Pelanggan</Button>
      </div>

      <div className="flex items-center gap-4">
        <Input
          placeholder="Cari nama atau ID..."
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          className="max-w-sm"
        />
      </div>

      <div className="border rounded-md">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>ID Pelanggan</TableHead>
              <TableHead>Nama</TableHead>
              <TableHead>Domisili</TableHead>
              <TableHead>Jenis Kelamin</TableHead>
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
              data?.data?.map((item: Pelanggan) => (
                <TableRow key={item.id}>
                  <TableCell>{item.id_pelanggan}</TableCell>
                  <TableCell>{item.nama}</TableCell>
                  <TableCell>{item.domisili}</TableCell>
                  <TableCell>{item.jenis_kelamin}</TableCell>
                  <TableCell className="text-right space-x-2">
                    <Button variant="outline" size="sm" onClick={() => handleOpenDialog(item)}>Edit</Button>
                    <Button variant="destructive" size="sm" onClick={() => { setSelectedPelanggan(item); setIsDeleteOpen(true); }}>Hapus</Button>
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
            <DialogTitle>{selectedPelanggan ? 'Edit Pelanggan' : 'Tambah Pelanggan'}</DialogTitle>
            <DialogDescription>
              Isi formulir di bawah ini untuk {selectedPelanggan ? 'mengupdate' : 'menambah'} data pelanggan.
            </DialogDescription>
          </DialogHeader>
          <form onSubmit={handleSubmit}>
            <div className="space-y-4 py-4">
              <div className="space-y-2">
                <Label htmlFor="nama">Nama</Label>
                <Input
                  id="nama"
                  value={formData.nama}
                  onChange={(e) => setFormData({ ...formData, nama: e.target.value })}
                  required
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="domisili">Domisili</Label>
                <Input
                  id="domisili"
                  value={formData.domisili}
                  onChange={(e) => setFormData({ ...formData, domisili: e.target.value })}
                  required
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="jenis_kelamin">Jenis Kelamin</Label>
                <Select
                  value={formData.jenis_kelamin}
                  onValueChange={(value: 'PRIA' | 'WANITA') => setFormData({ ...formData, jenis_kelamin: value })}
                >
                  <SelectTrigger>
                    <SelectValue placeholder="Pilih Jenis Kelamin" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="PRIA">PRIA</SelectItem>
                    <SelectItem value="WANITA">WANITA</SelectItem>
                  </SelectContent>
                </Select>
              </div>
            </div>
            <DialogFooter>
              <Button type="button" variant="outline" onClick={() => setIsOpen(false)}>Batal</Button>
              <Button type="submit" disabled={createMutation.isPending || updateMutation.isPending}>
                {selectedPelanggan ? 'Update' : 'Simpan'}
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
              Tindakan ini tidak dapat dibatalkan. Ini akan menghapus data pelanggan{' '}
              <span className="font-semibold">{selectedPelanggan?.nama}</span>.
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
