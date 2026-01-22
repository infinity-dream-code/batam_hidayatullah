# Setup Instructions - Sistem Rekap Pembayaran Batam Hidayatullah

## Fitur yang Sudah Dibuat

✅ Halaman Login dengan desain clean dan professional (Bootstrap 5)
✅ Dashboard dengan sidebar minimalis
✅ Halaman Rekap Pembayaran dengan tabel data interaktif
✅ Filter dan pencarian real-time
✅ Summary cards untuk statistik pembayaran
✅ Logout functionality
✅ Authentication middleware
✅ Design professional tanpa gradient pelangi
✅ Responsive untuk mobile, tablet, dan desktop
✅ Tanpa perlu compile assets (Bootstrap via CDN)

## Teknologi yang Digunakan

- Laravel 10
- Bootstrap 5.3 (via CDN)
- Bootstrap Icons
- MySQL/MariaDB

## Langkah-langkah Setup

### 1. Setup Database

Pastikan sudah membuat database di MySQL/MariaDB:

```sql
CREATE DATABASE batam_hidayatullah;
```

### 2. Konfigurasi .env

File `.env` sudah ada, pastikan konfigurasi database sudah benar:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=batam_hidayatullah
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Install Dependencies PHP

Jalankan perintah:

```bash
composer install
```

### 4. Generate Application Key (jika belum)

```bash
php artisan key:generate
```

### 5. Jalankan Migration

```bash
php artisan migrate
```

### 6. Jalankan Seeder (Membuat User Default)

```bash
php artisan db:seed --class=UserSeeder
```

**CATATAN:** Jika database sudah punya tabel users dengan field tambahan (kode, jabatan_id), seeder akan otomatis mengisi field tersebut.

### 7. Jalankan Server Laravel

```bash
php artisan serve
```

Atau jika menggunakan Laragon, akses langsung melalui browser:
```
http://batam_hidayatullah.test
```

**TIDAK PERLU npm install atau npm run dev** - Bootstrap dimuat via CDN!

## Login Credentials

Setelah menjalankan seeder, gunakan kredensial berikut untuk login:

- **Email**: admin@batamhidayatullah.com
- **Password**: password123

## Struktur File

```
app/Http/Controllers/
├── AuthController.php         # Controller untuk login/logout
└── DashboardController.php    # Controller untuk rekap pembayaran

resources/views/
├── auth/
│   └── login.blade.php        # Halaman login
├── layouts/
│   └── dashboard.blade.php    # Layout dashboard dengan sidebar
└── dashboard/
    └── rekap-pembayaran.blade.php  # Halaman rekap pembayaran

database/seeders/
└── UserSeeder.php             # Seeder untuk membuat user default

routes/
└── web.php                    # Routes
```

## Design Highlights

### Halaman Login
- Background clean (abu-abu terang)
- Card minimalis dengan shadow halus
- Dark navy sidebar color (#1e293b)
- Icon shield untuk logo
- Alert error yang clean
- **TIDAK ADA demo login info**
- Fully responsive

### Dashboard
- **TIDAK ADA menu beranda**
- **HANYA menu Rekap Pembayaran**
- Sidebar dark navy (#1e293b)
- Layout clean dan professional
- User info di sidebar dan top bar

### Halaman Rekap Pembayaran
- 3 Summary cards (Total, Lunas, Belum Lunas)
- Table dengan border minimal
- Filter & search interaktif
- Status badges clean
- Action buttons (View, Edit, Delete)
- Pagination

## Color Scheme (Professional & Clean)

- **Primary**: Dark Navy (#1e293b) - Sidebar & buttons
- **Background**: Light Gray (#f8f9fa)
- **White**: Card backgrounds
- **Success**: Bootstrap Green - Status Lunas
- **Warning**: Bootstrap Warning - Belum Lunas
- **Text**: Dark gray (#1e293b) untuk headings

## Flow Aplikasi

1. User buka `localhost:8000` → redirect ke `/login`
2. Login dengan email & password
3. Setelah login → **langsung ke `/rekap-pembayaran`**
4. Tidak ada halaman beranda, langsung ke data pembayaran
5. Logout → kembali ke login

## Fitur Rekap Pembayaran

- ✅ View semua data pembayaran
- ✅ Filter berdasarkan kelas
- ✅ Filter berdasarkan status (Lunas/Belum Lunas)
- ✅ Pencarian nama siswa real-time
- ✅ Summary statistics
- ✅ Action buttons untuk setiap row
- ✅ Responsive table

## Catatan Penting

✅ **TIDAK PERLU npm install atau npm run dev**
✅ **Langsung bisa digunakan** setelah setup database
✅ Data pembayaran masih dummy, untuk implementasi real buat model & migration
✅ Design clean & professional, tidak pakai gradient pelangi

## Troubleshooting

### 1. Error field 'kode' atau 'jabatan_id' tidak ada

Jika database baru dan belum ada tabel users:
```bash
php artisan migrate:fresh --seed
```

Jika database sudah ada tabel users dari project lain, seeder sudah disesuaikan untuk mengisi field tambahan tersebut.

### 2. Class not found
```bash
composer dump-autoload
```

### 3. View not found
```bash
php artisan view:clear
php artisan config:clear
```

### 4. Bootstrap tidak muncul
Pastikan koneksi internet aktif (Bootstrap via CDN)

## Development Notes

- Sistem ini didesain **khusus untuk admin saja**
- Tidak ada role/permission management
- Fokus pada rekap pembayaran
- Design clean, professional, corporate-style
- Tidak pakai warna-warna "AI style" atau gradient pelangi

---

Selamat menggunakan! 🎯
