# Platform Pemesanan Jasa Cleaning Service

Platform web berbasis PHP untuk pemesanan layanan cleaning service dengan panel admin yang lengkap.

## Fitur Utama

### Untuk Pelanggan:
- Melihat katalog layanan cleaning
- Melakukan pemesanan online
- Melihat status pesanan berdasarkan email
- Interface yang responsif dan user-friendly

### Untuk Admin:
- Login sistem admin
- Kelola katalog jasa (CRUD operations)
- Kelola pesanan (update status dari requested ke approved)
- Dashboard dengan statistik
- Panel admin yang modern

## Teknologi yang Digunakan

- **Backend**: PHP 7.4+ dengan PDO
- **Database**: MySQL
- **Frontend**: Bootstrap 5, HTML5, CSS3, JavaScript
- **Icons**: Font Awesome 6
- **Additional**: SweetAlert2, DataTables

## Instalasi

### Persyaratan Sistem
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Web server (Apache/Nginx)
- XAMPP/WAMP (untuk development lokal)

### Langkah Instalasi

1. **Clone atau Download Project**
   ```bash
   git clone [repository-url]
   # atau download dan extract file ZIP
   ```

2. **Setup Database**
   - Buka phpMyAdmin atau MySQL client
   - Import file `database.sql`
   - Database `cleaning_service` akan dibuat otomatis

3. **Konfigurasi Database**
   - Edit file `config/database.php`
   - Sesuaikan pengaturan database:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'cleaning_service');
   ```

4. **Upload ke Web Server**
   - Copy semua file ke direktori web server
   - Pastikan folder memiliki permission yang tepat

5. **Akses Website**
   - Frontend: `http://localhost/[folder-name]/`
   - Admin Panel: `http://localhost/[folder-name]/admin/`

## Login Admin

**Username**: `admin`  
**Password**: `password`

## Struktur Database

### Tabel `admin`
- Menyimpan data administrator
- Password di-hash menggunakan PHP password_hash()

### Tabel `services`
- Menyimpan katalog layanan cleaning
- Kategori: residential, commercial, deep-cleaning, maintenance
- Status: active/inactive

### Tabel `bookings`
- Menyimpan data pemesanan pelanggan
- Status: requested, approved, in_progress, completed, cancelled
- Relasi dengan tabel services

## Fitur Detail

### Dashboard Admin
- Statistik total layanan, pesanan, dan pendapatan
- Grafik pesanan bulanan
- Daftar pesanan terbaru
- Quick actions untuk manajemen

### Kelola Layanan
- Tambah layanan baru
- Edit layanan existing
- Hapus layanan
- Filter berdasarkan kategori dan status
- DataTables untuk pencarian dan sorting

### Kelola Pesanan
- View semua pesanan dengan detail lengkap
- Update status pesanan (requested → approved)
- Filter berdasarkan status dan tanggal
- Modal detail untuk informasi lengkap

### Frontend Features
- Hero section dengan call-to-action
- Katalog layanan dengan filter
- Form pemesanan yang user-friendly
- Tracking pesanan berdasarkan email
- Design responsif untuk mobile

## Keamanan

- Password admin di-hash dengan bcrypt
- Prepared statements untuk mencegah SQL injection
- Session management untuk admin authentication
- Input validation dan sanitization
- CSRF protection pada form

## Customization

### Menambah Kategori Layanan
Edit array `$categories` di file yang relevan:
```php
$categories = [
    'residential' => 'Rumah Tinggal',
    'commercial' => 'Komersial',
    'deep-cleaning' => 'Deep Cleaning',
    'maintenance' => 'Maintenance',
    'new-category' => 'Kategori Baru'
];
```

### Mengubah Status Pesanan
Edit array `$status_options` untuk menambah status baru:
```php
$status_options = [
    'requested' => 'Menunggu Persetujuan',
    'approved' => 'Disetujui',
    'new-status' => 'Status Baru'
];
```

### Styling
- Edit CSS di `includes/header.php` untuk frontend
- Edit CSS di `admin/includes/header.php` untuk admin panel
- Gunakan Bootstrap classes untuk styling cepat

## Troubleshooting

### Database Connection Error
- Periksa konfigurasi di `config/database.php`
- Pastikan MySQL service berjalan
- Cek username/password database

### Admin Login Gagal
- Pastikan tabel admin sudah ter-import
- Reset password admin jika perlu:
```sql
UPDATE admin SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE username = 'admin';
```

### Permission Error
- Set permission folder ke 755
- Set permission file ke 644
- Pastikan web server bisa akses folder

## Support

Untuk pertanyaan atau bantuan, silakan buat issue di repository ini atau hubungi developer.

## License

Project ini dibuat untuk keperluan edukasi dan dapat digunakan secara bebas.