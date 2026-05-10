import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '../lib/api';
import { Pelanggan } from '../lib/types';

export function usePelanggan(params: any) {
    return useQuery({
        queryKey: ['pelanggan', params],
        queryFn: async () => {
            const res = await api.get('/master/pelanggan', { params });
            return res.data;
        },
    });
}

export function useCreatePelanggan() {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: async (data: any) => {
            const res = await api.post('/master/pelanggan', data);
            return res.data;
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['pelanggan'] });
        },
    });
}

export function useUpdatePelanggan() {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: async ({ id, data }: { id: number; data: any }) => {
            const res = await api.put(`/master/pelanggan/${id}`, data);
            return res.data;
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['pelanggan'] });
        },
    });
}

export function useDeletePelanggan() {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: async (id: number) => {
            const res = await api.delete(`/master/pelanggan/${id}`);
            return res.data;
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['pelanggan'] });
        },
    });
}

export function usePelangganAll() {
    return useQuery({
        queryKey: ['pelanggan', 'all'],
        queryFn: async () => {
            const res = await api.get('/master/pelanggan/all');
            return res.data;
        },
    });
}
