# Frontend — Next.js Web Application

Mini Project bagian frontend yang dibangun dengan Next.js 16 dan React 19.

---

## Tech Stack

- **Framework**: Next.js 16 (App Router)
- **UI**: React 19, TypeScript, Tailwind CSS v4
- **Components**: Shadcn/UI
- **Data Fetching**: TanStack React Query v5
- **HTTP Client**: Axios
- **Notifications**: Sonner (toast)
- **Icons**: Lucide React
- **Theme**: next-themes (light mode)

---

## Struktur Direktori

```
frontend/src/
├── app/
│   ├── (dashboard)/            # Protected layout dengan sidebar navigasi
│   │   ├── layout.tsx          # Dashboard layout (sidebar + header)
│   │   ├── barang/             # Halaman CRUD Barang
│   │   ├── pelanggan/          # Halaman CRUD Pelanggan
│   │   ├── penjualan/          # Halaman CRUD Penjualan
│   │   └── item-penjualan/     # Halaman laporan Item Penjualan (read-only)
│   ├── auth/                   # Halaman login & register
│   ├── pos/                    # POS mode (fullscreen, standalone layout)
│   ├── shop/                   # Halaman publik (opsional)
│   ├── layout.tsx              # Root layout
│   └── page.tsx                # Redirect ke /penjualan atau /auth/login
├── components/
│   ├── layout/
│   │   └── app-sidebar.tsx     # Sidebar navigasi utama
│   └── ui/                     # Shadcn/UI components
├── hooks/                      # Custom React hooks (data fetching)
│   ├── use-barang.ts
│   ├── use-pelanggan.ts
│   ├── use-penjualan.ts
│   └── use-item-penjualan.ts
└── lib/
    ├── api.ts                  # Axios instance dengan base URL & interceptors
    ├── auth.tsx                # Auth context (React Context)
    └── types.ts                # TypeScript type definitions
```

---

## Setup

```bash
cp .env.example .env.local

# Edit .env.local
# NEXT_PUBLIC_API_URL=http://localhost:8000/api/v1

npm install
npm run dev
```

Aplikasi berjalan di: `http://localhost:3000`

---

## Environment Variables

| Variable | Deskripsi | Contoh |
|----------|-----------|--------|
| `NEXT_PUBLIC_API_URL` | Base URL backend API | `http://localhost:8000/api/v1` |

---

## Halaman & Fitur

### Dashboard (Protected — butuh login)

| Route | Halaman | Fitur |
|-------|---------|-------|
| `/penjualan` | Data Penjualan | CRUD transaksi, multi-item, kalkulasi subtotal, pagination |
| `/pelanggan` | Data Pelanggan | CRUD pelanggan, search, pagination |
| `/barang` | Data Barang | CRUD barang, filter kategori, search, pagination |
| `/item-penjualan` | Laporan Item | Read-only, filter tanggal, search, pagination |

### Auth

| Route | Deskripsi |
|-------|-----------|
| `/auth/login` | Halaman login |
| `/auth/register` | Halaman register |

### POS Mode

| Route | Deskripsi |
|-------|-----------|
| `/pos` | Antarmuka kasir fullscreen. Pilih pelanggan & item, proses transaksi tanpa navigasi |

---

## State Management

| Kebutuhan | Solusi |
|-----------|--------|
| Auth state (user session) | React Context (`lib/auth.tsx`) |
| Server state & caching | TanStack React Query v5 |
| Form state | `useState` lokal per komponen |

---

## Pola Penggunaan Hook

Setiap entitas data punya hook sendiri di `src/hooks/`:

```typescript
// Contoh penggunaan
const { data, isLoading } = usePenjualan({ search, page });
const createMutation = useCreatePenjualan();

// Trigger mutasi
await createMutation.mutateAsync(formData);
```

React Query menangani caching, invalidation, dan refetch otomatis setelah mutasi.

---

## Komponen UI

Menggunakan [Shadcn/UI](https://ui.shadcn.com/) yang di-generate ke `src/components/ui/`. Komponen yang dipakai antara lain:

- `Table`, `TableRow`, `TableCell` — untuk tabel data
- `Dialog`, `AlertDialog` — untuk modal form dan konfirmasi hapus
- `Select`, `Input`, `Label` — untuk form input
- `Button` — dengan berbagai `variant` (outline, destructive, ghost)
- `Skeleton` — untuk loading state
- `Sonner` (toast) — untuk notifikasi sukses/error

---

## Scripts

```bash
npm run dev      # Development server (localhost:3000)
npm run build    # Build production bundle
npm run start    # Jalankan production build
npm run lint     # ESLint check
```
