# Panduan Integrasi Aiven MySQL & Deployment Vercel

Proyek ini telah dikonfigurasi untuk terhubung dengan Aiven MySQL dan siap di-deploy ke Vercel. 
Berikut adalah langkah-langkah selanjutnya yang perlu Anda lakukan.

## 1. Import Database dari Local (XAMPP/Laragon) ke Aiven MySQL

Untuk memindahkan data dari database lokal `tif_new` ke Aiven, Anda bisa menggunakan Terminal/Command Prompt atau aplikasi GUI seperti DBeaver / HeidiSQL.

### Cara 1: Menggunakan CMD / Terminal (mysqldump & mysql)

**Langkah A: Export dari Local**
Buka terminal dan jalankan perintah berikut untuk mengekstrak database lokal Anda menjadi file `.sql`:
```bash
mysqldump -u root tif_new > tif_new_backup.sql
```
*(Jika mysql root Anda menggunakan password, tambahkan `-p` setelah `-u root`)*

**Langkah B: Import ke Aiven MySQL**
Setelah file `tif_new_backup.sql` berhasil dibuat, jalankan perintah ini untuk melakukan import ke Aiven:
```bash
mysql -h mysql-16b3f798-ahmedseptiyanto97-6573.f.aivencloud.com -P 28472 -u avnadmin -p defaultdb < tif_new_backup.sql
```
*Sistem akan meminta password. Masukkan password Aiven Anda (lihat di dashboard Aiven)*

---

### Cara 2: Menggunakan DBeaver / HeidiSQL / MySQL Workbench
1. Buka aplikasi database client Anda.
2. Buat koneksi baru dengan detail Aiven:
   - **Host:** `mysql-16b3f798-ahmedseptiyanto97-6573.f.aivencloud.com`
   - **Port:** `28472`
   - **Database:** `defaultdb`
   - **Username:** `avnadmin`
   - **Password:** *(lihat di dashboard Aiven)*
   - *Pastikan opsi SSL/TLS Mode diaktifkan (Required)* karena layanan cloud database seperti Aiven wajib menggunakan SSL.
3. Setelah berhasil terhubung, Anda bisa menggunakan fitur **Import/Execute SQL Script** dari file `.sql` hasil export dari localhost.

---

## 2. Mengatur File `.env` Local (Opsional)
Jika Anda ingin langsung menggunakan database Aiven saat mengembangkan di komputer lokal, silakan ubah bagian DB di file `.env` milik Anda menjadi:
```env
DB_CONNECTION=mysql
DB_HOST=mysql-16b3f798-ahmedseptiyanto97-6573.f.aivencloud.com
DB_PORT=28472
DB_DATABASE=defaultdb
DB_USERNAME=avnadmin
DB_PASSWORD=YOUR_AIVEN_PASSWORD_HERE
```
*Catatan: Pastikan untuk menjalankan `php artisan config:clear` jika `.env` tidak langsung terbaca.*

---

## 3. Panduan Deployment Vercel

Kami telah membuatkan file `vercel.json` dan `api/index.php` untuk menangani filesystem serverless dari Vercel.

**Langkah-langkah di Vercel:**
1. Upload folder `TIF_NEW` ini ke GitHub.
2. Pastikan folder `public/build` (hasil compile dari Vite) **TIDAK** di-ignore di `.gitignore` dan **ikut di-push** ke GitHub. Jika folder ini tidak ada di github, tampilan CSS/JS Anda tidak akan termuat di Vercel. Jalankan `npm run build` lokal terlebih dahulu sebelum push.
3. Buka dashboard [Vercel](https://vercel.com) dan buat Project baru ("Add New Project").
4. Hubungkan dengan repository GitHub Anda.
5. Pada bagian **"Environment Variables"**, Vercel seharusnya akan membaca otomatis beberapa variabel dari `vercel.json`. Namun, sangat disarankan menambahkan secara manual setidaknya:
   - `DB_PASSWORD`: *(password Aiven Anda, lihat di dashboard Aiven)* *(Ini sangat penting diletakkan di Vercel rahasia agar aman, kami mensengaja tidak memasukannya langsung ke vercel.json `DB_PASSWORD` secara eksplisit, sebaiknya Anda isi dari Vercel Dashboard -> Settings -> Environment Variables)*
   - `APP_KEY`: *(Isikan sama persis seperti yang ada di file .env lokal Anda)*
6. Klik **Deploy**.

Proyek Anda akan langsung live di Vercel dengan database MySQL yang ditenagai oleh Aiven!
