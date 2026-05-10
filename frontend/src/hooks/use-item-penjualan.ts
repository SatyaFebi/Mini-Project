import { useQuery } from '@tanstack/react-query';
import api from '../lib/api';

interface ItemPenjualanParams {
    search?: string;
    page?: number;
    date_from?: string;
    date_to?: string;
}

export function useItemPenjualan(params: ItemPenjualanParams) {
    return useQuery({
        queryKey: ['item-penjualan', params],
        queryFn: async () => {
            const res = await api.get('/transaksi/item-penjualan', { params });
            return res.data;
        },
    });
}
