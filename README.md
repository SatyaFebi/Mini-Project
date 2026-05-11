# Mini Project

Mini Project sederhana yang dibangun sebagai bagian dari tahap perekrutan Full-Stack Developer di PT Unggul Mitra Solusi.

---

## Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | Laravel 12, PHP 8.2+ |
| Frontend | Next.js 16, React 19, TypeScript |
| Database | MySQL |
| Authentication | Laravel Sanctum (HttpOnly Cookie) |
| API Docs | Swagger UI (L5-Swagger / OpenAPI 3.0) |
| UI Components | Shadcn/UI, Tailwind CSS v4 |
| State & Fetching | TanStack React Query v5, Axios |

---

## Struktur Proyek

```
Mini_Project/
├── backend/    # Laravel REST API
└── frontend/   # Next.js web application
```

---

## Fitur Utama

- **Autentikasi** — Register & Login dengan session berbasis HttpOnly Cookie (Sanctum)
- **Master Pelanggan** — CRUD data pelanggan dengan auto-generate ID (`PELANGGAN_N`)
- **Master Barang** — CRUD data barang per kategori
- **Transaksi Penjualan** — Buat, edit, hapus transaksi dengan multi-item dan kalkulasi subtotal otomatis
- **Laporan Item Penjualan** — Rekap item yang terjual dengan filter tanggal dan pencarian
- **POS Mode** — Antarmuka kasir fullscreen untuk proses transaksi cepat
- **API Documentation** — Swagger UI di `/api/documentation`

---

## Quick Start

### Prerequisites

- PHP >= 8.2 & Composer
- Node.js >= 18 & npm
- MySQL

### 1. Clone repo

```bash
git clone https://github.com/SatyaFebi/Mini-Project.git
cd Mini-Project
```

### 2. Setup Backend

```bash
cd backend
cp .env.example .env
# Edit .env — isi DB_DATABASE, DB_USERNAME, DB_PASSWORD
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Backend running di: `http://localhost:8000`

### 3. Setup Frontend

```bash
cd frontend
cp .env.example .env.local
# Edit .env.local — isi NEXT_PUBLIC_API_URL=http://localhost:8000/api/v1
npm install
npm run dev
```

Frontend running di: `http://localhost:3000`

---

## API Documentation

Setelah backend running, buka:

```
http://localhost:8000/api/documentation
```

---

## Database

Skema database menggunakan MySQL dengan 4 tabel utama:

| Tabel | Deskripsi |
|-------|-----------|
| `pelanggans` | Data pelanggan (soft delete) |
| `barangs` | Data barang/produk |
| `penjualans` | Header transaksi penjualan |
| `item_penjualans` | Detail item per transaksi |

Untuk reset & isi data awal:

```bash
cd backend
php artisan migrate:fresh --seed
```
