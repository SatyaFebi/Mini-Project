import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '../lib/api';
// import { Barang } from '../lib/types';

export function useBarang(params: any) {
    return useQuery({
        queryKey: ['barang', params],
        queryFn: async () => {
            const res = await api.get('/master/barang', { params });
            return res.data;
        },
    });
}

export function useCreateBarang() {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: async (data: any) => {
            const res = await api.post('/master/barang', data);
            return res.data;
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['barang'] });
            queryClient.invalidateQueries({ queryKey: ['categories'] });
        },
    });
}

export function useUpdateBarang() {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: async ({ id, data }: { id: number; data: any }) => {
            const res = await api.put(`/master/barang/${id}`, data);
            return res.data;
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['barang'] });
            queryClient.invalidateQueries({ queryKey: ['categories'] });
        },
    });
}

export function useDeleteBarang() {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: async (id: number) => {
            const res = await api.delete(`/master/barang/${id}`);
            return res.data;
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['barang'] });
        },
    });
}

export function useBarangAll() {
    return useQuery({
        queryKey: ['barang', 'all'],
        queryFn: async () => {
            const res = await api.get('/master/barang/all');
            return res.data;
        },
    });
}

export function useCategories() {
    return useQuery({
        queryKey: ['categories'],
        queryFn: async () => {
            // Simulating fetch for categories based on backend validation rules
            return new Promise<string[]>((resolve) => {
                setTimeout(() => {
                    resolve(['ATK', 'MASAK', 'RT', 'ELEKTRONIK', 'LAINNYA']);
                }, 300);
            });
        },
        staleTime: 1000 * 60 * 5, // Cache for 5 minutes
    });
}
