'use client';

import { useAuth } from '@/lib/auth';
import { useRouter } from 'next/navigation';
import { useEffect } from 'react';
import AppSidebar from '@/components/layout/app-sidebar';
import Header from '@/components/layout/header';
import { SidebarProvider } from '@/components/ui/sidebar';
import { QueryClient, QueryClientProvider, QueryCache } from '@tanstack/react-query';
import { toast } from 'sonner';

const queryClient = new QueryClient({
  queryCache: new QueryCache({
    onError: (error: any) => {
      toast.error(error.response?.data?.message || 'Terjadi kesalahan saat mengambil data.');
    },
  }),
});

export default function DashboardLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  const { isAuthenticated, isLoading } = useAuth();
  const router = useRouter();

  useEffect(() => {
    if (!isLoading && !isAuthenticated) {
      router.push('/auth/login');
    }
  }, [isAuthenticated, isLoading, router]);

  if (isLoading) {
     return <div className="flex h-screen items-center justify-center">
        <svg className="mr-3 size-5 animate-spin ..." viewBox="0 0 24 24">
          <path
            fill="currentColor"
            d="M12 22c-5.523 0-10-4.477-10-10s4.477-10 10-10 10 4.477 10 10-4.477 10-10 10zm0-18c-4.418 0-8 3.582-8 8s3.582 8 8 8 8-3.582 8-8-3.582-8-8-8z"
          />
          <path
            fill="currentColor"
            d="M19 12c0-3.866-3.134-7-7-7v4a3 3 0 0 0 3 3 3 3 0 0 0 3-3z"
          />
        </svg>
        Loading...
     </div>;
  }

  if (!isAuthenticated) {
    return null;
  }

  return (
    <QueryClientProvider client={queryClient}>
      <SidebarProvider>
        <div className="flex h-screen w-full">
          <AppSidebar />
          <div className="flex flex-1 flex-col overflow-hidden">
            <Header />
            <main className="flex-1 overflow-y-auto p-6">
              {children}
            </main>
          </div>
        </div>
      </SidebarProvider>
    </QueryClientProvider>
  );
}
