# Backend — Laravel REST API

Mini Project bagian backend yang dibangun dengan Laravel 12.

---

## Tech Stack

- **Framework**: Laravel 12
- **PHP**: >= 8.2
- **Database**: MySQL
- **Authentication**: Laravel Sanctum (token-based, HttpOnly cookie)
- **API Docs**: L5-Swagger (OpenAPI 3.0)
- **Testing**: PestPHP

---

## Struktur Direktori

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/
│   │   │   ├── Auth/           # AuthController (login, register, logout, me)
│   │   │   ├── Master/         # PelangganController, BarangController
│   │   │   └── Transaksi/      # PenjualanController, ItemPenjualanController
│   │   ├── Requests/           # Form Request validation
│   │   │   ├── Master/
│   │   │   └── Transaksi/
│   │   ├── Resources/          # API Resource transformers
│   │   │   ├── Master/
│   │   │   └── Transaksi/
│   │   ├── Services/           # Business logic layer
│   │   │   ├── Master/
│   │   │   └── Transaksi/
│   │   └── Swagger.php         # Global OpenAPI info & server config
│   └── Models/
│       ├── Pelanggan.php
│       ├── Barang.php
│       ├── Penjualan.php
│       ├── ItemPenjualan.php
│       └── User.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── DataSeeder.php      # Sample data (10 pelanggan, 10 barang, 10 nota)
└── routes/
    └── api.php
```

---

## Setup

```bash
cp .env.example .env

# Isi konfigurasi database di .env
# DB_DATABASE=nama_database
# DB_USERNAME=root
# DB_PASSWORD=

composer install
php artisan key:generate
php artisan migrate --seed
php artisan l5-swagger:generate
php artisan serve
```

---

## Environment Variables

| Variable | Deskripsi | Default |
|----------|-----------|---------|
| `APP_URL` | URL aplikasi backend | `http://localhost` |
| `DB_CONNECTION` | Driver database | `mysql` |
| `DB_HOST` | Host database | `127.0.0.1` |
| `DB_PORT` | Port database | `3306` |
| `DB_DATABASE` | Nama database | `backend` |
| `DB_USERNAME` | Username database | `root` |
| `DB_PASSWORD` | Password database | _(kosong)_ |
| `SANCTUM_STATEFUL_DOMAINS` | Domain frontend yang diizinkan | `localhost:3000` |

---

## API Endpoints

Base URL: `/api/v1`

### Auth

| Method | Endpoint | Akses | Deskripsi |
|--------|----------|-------|-----------|
| `POST` | `/register` | Public | Register user baru |
| `POST` | `/login` | Public | Login, mengembalikan token |
| `POST` | `/logout` | Auth | Logout, hapus token |
| `GET` | `/me` | Auth | Data user yang sedang login |

### Master — Pelanggan

| Method | Endpoint | Akses | Deskripsi |
|--------|----------|-------|-----------|
| `GET` | `/master/pelanggan` | Auth | List pelanggan (paginated, searchable) |
| `GET` | `/master/pelanggan/all` | Public | Semua pelanggan (tanpa pagination) |
| `POST` | `/master/pelanggan` | Auth | Tambah pelanggan (ID auto-generate: `PELANGGAN_N`) |
| `PUT` | `/master/pelanggan/{id}` | Auth | Update pelanggan |
| `DELETE` | `/master/pelanggan/{id}` | Auth | Hapus pelanggan (soft delete) |

### Master — Barang

| Method | Endpoint | Akses | Deskripsi |
|--------|----------|-------|-----------|
| `GET` | `/master/barang` | Auth | List barang (paginated, searchable) |
| `GET` | `/master/barang/all` | Public | Semua barang (tanpa pagination) |
| `POST` | `/master/barang` | Auth | Tambah barang |
| `PUT` | `/master/barang/{id}` | Auth | Update barang |
| `DELETE` | `/master/barang/{id}` | Auth | Hapus barang |

### Transaksi — Penjualan

| Method | Endpoint | Akses | Deskripsi |
|--------|----------|-------|-----------|
| `GET` | `/transaksi/penjualan` | Auth | List penjualan (paginated, terbaru di atas) |
| `POST` | `/transaksi/penjualan` | Public | Buat transaksi baru (untuk POS guest) |
| `PUT` | `/transaksi/penjualan/{id}` | Auth | Update transaksi |
| `DELETE` | `/transaksi/penjualan/{id}` | Auth | Hapus transaksi |

### Transaksi — Item Penjualan

| Method | Endpoint | Akses | Deskripsi |
|--------|----------|-------|-----------|
| `GET` | `/transaksi/item-penjualan` | Auth | List item (paginated, filter: search, date_from, date_to) |

---

## Database Schema

```
pelanggans
  id, ID_PELANGGAN (PK string), NAMA, DOMISILI, JENIS_KELAMIN, deleted_at

barangs
  id, KODE (PK string), NAMA, KATEGORI, HARGA

penjualans
  id, ID_NOTA (unique), TGL, KODE_PELANGGAN (FK), SUBTOTAL

item_penjualans
  id, NOTA (FK → ID_NOTA), KODE_BARANG (FK), Qty
```

---

## Auto-generated IDs

| Model | Format | Contoh |
|-------|--------|--------|
| `Pelanggan` | `PELANGGAN_N` | `PELANGGAN_11` |
| `Penjualan` | `NOTA_N` | `NOTA_11` |

Keduanya di-generate otomatis via event `creating` di masing-masing Model.

---

## API Documentation (Swagger)

```bash
# Generate ulang docs setelah ada perubahan anotasi
php artisan l5-swagger:generate
```

Akses Swagger UI: `http://localhost:8000/api/documentation`

---

## Testing

```bash
php artisan test
# atau
./vendor/bin/pest
```
