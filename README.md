# Kopi App (E-Commerce & Brand Awareness)

Aplikasi web kopi terpadu: Brand Awareness/Portofolio + E-Commerce retail multi-kategori (B2C) + Wholesale (B2B).

## Arsitektur Monorepo
```
kopi-app/
├── backend/       # Laravel 12 + Filament v5 Panel (/admin) + Sanctum + Spatie
├── frontend/      # React 19 + Vite + TypeScript + TanStack Query + Zustand
├── docs/          # Dokumentasi teknis & ERD
└── README.md      # Panduan setup & eksekusi
```

---

## 1. Menjalankan Backend (Laravel 12 + Filament v5)

### Prasyarat
- PHP >= 8.2 (ekstensi `pdo_mysql`, `fileinfo`, `gd`/`imagick`, `zip`)
- MySQL / MariaDB (sudah terpasang di Laragon atau mandiri)
- Composer

### Langkah Instalasi
```bash
cd backend

# Salin konfigurasi environment
cp .env.example .env

# Generate application key
php artisan key:generate

# Buat symbolic link storage
php artisan storage:link

# Jalankan migrasi database
php artisan migrate

# Jalankan server development
php artisan serve
```

- **Akses Aplikasi**: `http://localhost:8000`
- **Akses Panel Admin Filament**: `http://localhost:8000/admin` (Panel login)

---

## 2. Menjalankan Frontend (React + Vite + TypeScript)

### Prasyarat
- Node.js >= 18
- npm

### Langkah Instalasi
```bash
cd frontend

# Install dependensi
npm install

# Jalankan development server
npm run dev
```

- **Akses Storefront**: `http://localhost:5173`

---

## 3. Skema & Konvensi
- Mata uang: Selalu integer Rupiah (`unsignedBigInteger`).
- Storage upload: Membaca driver `FILESYSTEM_DISK` dari `.env` (default: `public`) dengan symlink ke `storage/app/public`.
- Dokumentasi ERD dan tabel lengkap: lihat [`docs/erd.md`](docs/erd.md).
