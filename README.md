# SAANS Assessment SPMB — Panduan Deployment aaPanel

Panduan lengkap instalasi dan deployment aplikasi **SAANS Assessment SPMB (Sekolah Alam Anak Saleh)** pada server VPS yang menggunakan control panel **aaPanel**.

---

## 📋 Spesifikasi & Kebutuhan Server

Pastikan paket-paket berikut sudah terinstall di aaPanel (melalui menu **App Store**):

| Komponen | Versi Rekomendasi | Keterangan |
| :--- | :--- | :--- |
| **Web Server** | Nginx 1.22+ (atau 1.24 / 1.26) | Mendukung URL rewrite Laravel |
| **PHP** | **PHP 8.3** (atau 8.4) | Sesuai requirement `composer.json` |
| **Database** | MySQL 5.7 / 8.0 atau MariaDB 10.6+ | |
| **Node.js** | Node.js 18+ / 20+ (Opsional) | Hanya jika build aset langsung di server |
| **Composer** | Composer 2.x | Terintegrasi di terminal aaPanel |

### Ekstensi PHP yang Wajib Aktif (di aaPanel: App Store > PHP 8.3 > Setting > Install extensions):
- `fileinfo`
- `pdo_mysql`
- `mbstring`
- `openssl`
- `curl`
- `tokenizer`
- `xml`
- `zip`
- `bcmath`

---

## 🚀 Langkah-Langkah Deployment ke aaPanel

### Langkah 1: Persiapan File Project (Lokal)

Sebelum mengunggah ke server, lakukan kompilasi aset frontend di komputer lokal Anda:

```bash
# 1. Pastikan aset Vite sudah terkompilasi
npm run build
```

Setelah build selesai:
1. Pastikan folder `public/build` sudah terisi file `.css` dan `.js`.
2. Kompres seluruh file project ke format `.zip` (misal: `saans-assessment.zip`).
   > ⚠️ **Catatan Penting**: **JANGAN** sertakan folder `vendor/` dan `node_modules/` ke dalam file `.zip` agar ukuran file kecil dan tidak corrupt. Keduanya akan diinstall langsung di server.

---

### Langkah 2: Buat Database di aaPanel

1. Masuk ke dashboard aaPanel.
2. Buka menu **Database** > klik **Add Database**.
3. Isi informasi database:
   - **DBName**: `sans_assessment` (atau sesuaikan)
   - **DBUser**: `sans_user`
   - **Password**: Buat password yang kuat (simpan untuk konfigurasi `.env`)
   - **Character Set**: `utf8mb4`
4. Klik **Submit**.

---

### Langkah 3: Tambah Website di aaPanel

1. Buka menu **Website** > klik **Add Site**.
2. Masukkan informasi:
   - **Domain name**: `spmb.anaksaleh.sch.id` (atau IP VPS Anda jika belum ada domain)
   - **Description**: SAANS Assessment SPMB
   - **Root directory**: `/www/wwwroot/spmb.anaksaleh.sch.id`
   - **PHP Version**: Pilih **PHP-83**
3. Klik **Submit**.

---

### Langkah 4: Upload & Ekstrak File Project

1. Buka menu **Files** di aaPanel.
2. Masuk ke direktori website Anda: `/www/wwwroot/spmb.anaksaleh.sch.id`.
3. Hapus file bawaan website (seperti `index.html` atau `404.html`).
4. Klik tombol **Upload**, unggah file `saans-assessment.zip`.
5. Klik kanan file `.zip` yang sudah terunggah > pilih **Uncompress** > ekstrak ke folder website tersebut.

---

### Langkah 5: Atur Running Directory & URL Rewrite (Nginx)

Ini adalah langkah krusial agar Laravel dapat diakses tanpa error 404 / 500:

1. Buka menu **Website** > klik nama domain Anda untuk membuka jendela pengaturan.
2. Pilih tab **Site directory**:
   - **Running directory**: Ubah dari `/` menjadi `/public`
   - Klik **Save**.
3. Pilih tab **URL rewrite**:
   - Pada dropdown template rewrite, pilih **laravel5** (berlaku untuk semua Laravel modern).
   - Pastikan kodenya seperti berikut:
     ```nginx
     location / {
         try_files $uri $uri/ /index.php?$query_string;
     }
     ```
   - Klik **Save**.

---

### Langkah 6: Konfigurasi File `.env`

1. Di menu **Files** aaPanel, buka folder project Anda.
2. Jika belum ada file `.env`, duplikasi file `.env.example` lalu ubah namanya menjadi `.env`.
3. Buka dan edit file `.env`, sesuaikan baris berikut:

```dotenv
APP_NAME="SAANS Assessment SPMB"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://spmb.anaksaleh.sch.id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sans_assessment
DB_USERNAME=sans_user
DB_PASSWORD=password_database_anda

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```
4. Simpan perubahan (`Ctrl + S` atau tombol Save).

---

### Langkah 7: Eksekusi Perintah Terminal di Server

Buka menu **Terminal** di aaPanel (atau via SSH menggunakan PuTTY / Terminal Anda):

```bash
# 1. Masuk ke direktori project website
cd /www/wwwroot/spmb.anaksaleh.sch.id

# 2. Install dependensi PHP via Composer
composer install --no-dev --optimize-autoloader

# 3. Generate Application Encryption Key
php artisan key:generate --force

# 4. Jalankan Migrasi Database
php artisan migrate --force

# 5. Jalankan Seeder Akun Administrator
php artisan db:seed --class=SPMBDbSeeder --force

# 6. Buat Storage Symlink
php artisan storage:link

# 7. Optimasi Cache Laravel untuk Production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

### Langkah 8: Atur Hak Akses & Permission Folder

Agar Laravel dapat menulis log, session, dan file upload:

```bash
# Jalankan di terminal:
chown -R www:www /www/wwwroot/spmb.anaksaleh.sch.id
chmod -R 755 /www/wwwroot/spmb.anaksaleh.sch.id
chmod -R 775 /www/wwwroot/spmb.anaksaleh.sch.id/storage
chmod -R 775 /www/wwwroot/spmb.anaksaleh.sch.id/bootstrap/cache
```

Atau melalui aaPanel **Files**:
- Klik kanan folder `storage` > **Permission** > ubah ke **775**, centang *Recurse*.
- Klik kanan folder `bootstrap/cache` > **Permission** > ubah ke **775**, centang *Recurse*.
- Pastikan Owner adalah `www`.

---

### Langkah 9: Pasang SSL (HTTPS) Gratis

1. Di menu **Website** aaPanel, klik nama website.
2. Buka tab **SSL** > pilih **Let's Encrypt**.
3. Centang domain Anda > klik **Apply**.
4. Aktifkan toggle **Force HTTPS**.

---

## 🔑 Kredensial Akun Administrator Default

Setelah database dimigrasi dan di-seed, gunakan akun Administrator berikut untuk masuk ke sistem:

- **Halaman Login**: `https://domain-anda/`
- **Email / NIP**: `admin@anaksaleh.sch.id`
- **Kata Sandi**: `admin123`
- **Peran**: Administrator SPMB Terpadu (Mengelola unit SD & SMP)

> 💡 **Fitur Admin Terpadu**:
> Administrator dapat memantau dan beralih antara unit **SD** dan **SMP** secara langsung melalui tombol pengalih **SD | SMP** di navbar dashboard atas.

---

## 🛠️ Panduan Troubleshooting di aaPanel

### 1. Error `500 | Server Error`
- **Penyebab**: File `.env` belum ada / `APP_KEY` kosong / permission storage salah.
- **Solusi**:
  ```bash
  php artisan key:generate --force
  chmod -R 775 storage bootstrap/cache
  chown -R www:www storage bootstrap/cache
  ```
  Periksa log error detail di: `storage/logs/laravel.log`.

### 2. Error `404 Not Found` pada Halaman Selain Home
- **Penyebab**: Konfigurasi Nginx URL Rewrite belum diterapkan.
- **Solusi**: Buka menu Website > **URL rewrite** > pilih template **laravel5** > Save.

### 3. Tampilan Hancur / CSS & JS Tidak Termuat
- **Penyebab**: Folder `public/build` belum ter-upload atau `APP_URL` di `.env` belum sesuai dengan protokol (`http://` vs `https://`).
- **Solusi**:
  1. Pastikan `APP_URL` di `.env` diawali `https://` jika SSL aktif.
  2. Pastikan folder `public/build` berisi manifest dan aset hasil kompilasi Vite.
  3. Jalankan: `php artisan optimize:clear`.

### 4. Update Kode di Kemudian Hari
Jika melakukan update kode:
```bash
git pull origin main # jika menggunakan git
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

Dikelola oleh Panitia SPMB & Tim IT Sekolah Alam Anak Saleh.
