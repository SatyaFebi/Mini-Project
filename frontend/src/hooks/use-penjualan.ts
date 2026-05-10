import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '../lib/api';
import { Penjualan } from '../lib/types';

export function usePenjualan(params: any) {
    return useQuery({
        queryKey: ['penjualan', params],
        queryFn: async () => {
            const res = await api.get('/transaksi/penjualan', { params });
            return res.data;
        },
    });
}

export function useCreatePenjualan() {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: async (data: any) => {
            const res = await api.post('/transaksi/penjualan', data);
            return res.data;
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['penjualan'] });
        },
    });
}

export function useUpdatePenjualan() {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: async ({ id, data }: { id: number; data: any }) => {
            const res = await api.put(`/transaksi/penjualan/${id}`, data);
            return res.data;
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['penjualan'] });
        },
    });
}

export function useDeletePenjualan() {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: async (id: number) => {
            const res = await api.delete(`/transaksi/penjualan/${id}`);
            return res.data;
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['penjualan'] });
        },
    });
}
