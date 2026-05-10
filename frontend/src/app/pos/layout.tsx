'use client';

import { QueryClient, QueryClientProvider, QueryCache } from '@tanstack/react-query';
import { toast } from 'sonner';

const queryClient = new QueryClient({
  queryCache: new QueryCache({
    onError: (error: any) => {
      toast.error(error.response?.data?.message || 'Terjadi kesalahan saat mengambil data.');
    },
  }),
});

export default function POSLayout({ children }: { children: React.ReactNode }) {
  return (
    <QueryClientProvider client={queryClient}>
      {children}
    </QueryClientProvider>
  );
}
