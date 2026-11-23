# Kodelio (nama sementara)
Stack: PHP 8.1+ (PDO), MySQL, Bootstrap 5, PHPMailer.

## Cara menjalankan
1. `composer install` (memasang PHPMailer). Tanpa Composer: unduh PHPMailer dari GitHub lalu salin folder `src` ke `includes/PHPMailer/src/`.
2. Import `database/schema.sql` lalu `database/seed.sql` ke MySQL.
3. Salin `config/config.example.php` menjadi `config/config.php`, isi database dan SMTP, serta `app_url`.
4. Taruh folder di web root (XAMPP/Laragon) atau jalankan `php -S localhost:8000`, lalu ubah `app_url` sesuai alamat itu.
5. Jadikan admin: `UPDATE users SET role='admin' WHERE email='emailmu@contoh.com';`

## Status pengerjaan
| Tahap | Status |
|---|---|
| 1 Landing page | Selesai |
| 2 Login, Register, verifikasi email, Forgot/Reset password | Selesai (perlu SMTP aktif untuk uji email) |
| 3 Database + autentikasi | Selesai: 21 tabel, seed 2 jalur, 20 level, 100 step (judul saja), 6 achievement |
| 4 Pemilihan jalur + placement test | Selesai: pilih jalur, tes kemampuan awal 6 soal, rekomendasi jalur |
| 4–5 Peta jalur belajar (kategori → level → step, kunci berurutan, awan penutup) | Selesai, data dari DB |
| 6–7 Halaman belajar + editor kode | Selesai: Learn, Understand the Code, Code Practice (CodeMirror + live preview sandbox), XP, streak, achievement First Step. Materi baru HTML Level 1 (step 1–3) |
| 8–9 Mini game + final quiz + Smart Feedback | Selesai: mini game 4 tipe (pilihan ganda, susun kode, cocokkan, cari bug), kuis dengan syarat 70%, hasil tersimpan. Soal baru HTML Level 1 |
| 10–14 Gamifikasi lengkap, riwayat, proyek, admin | Belum |

Keamanan yang sudah ada: password_hash, prepared statements, CSRF, token acak (hanya hash tersimpan, sekali pakai, kedaluwarsa), pesan reset yang tidak membocorkan email, escape output (XSS), folder `includes`, `config`, `database` diblokir lewat .htaccess (Apache).

`demo_mode` di config: true = tombol "Tandai selesai (demo)" di lesson.php aktif untuk menguji alur kunci. Matikan setelah materi, mini-game, dan kuis asli selesai.

## Cara clone dan menjalankan (untuk anggota tim)
Prasyarat: Git, PHP 8.1+, MySQL, dan Composer (paket XAMPP atau Laragon sudah mencakup PHP dan MySQL).

1. **Clone repo** ke folder web server (XAMPP: `C:\xampp\htdocs`, Laragon: `C:\laragon\www`):
   ```
   git clone https://github.com/kiatfalih/kodelio.git
   cd kodelio
   ```
2. **Pasang dependensi:** `composer install`
3. **Buat file konfigurasi** (file ini rahasia dan tidak ikut Git):
   ```
   copy config\config.example.php config\config.php     (Windows)
   cp config/config.example.php config/config.php         (Mac/Linux)
   ```
   Buka `config/config.php`, lalu isi `app_url` (contoh `http://localhost/kodelio`), data `db`, dan data `smtp`.
4. **Buat database:** buka phpMyAdmin, lalu import `database/schema.sql` dan setelah itu `database/seed.sql`.
Setelah import schema.sql dan seed.sql, jalankan berurutan:
a. database/migrate_tahap6.sql
b. database/seed_html_level1.sql
c. database/migrate_tahap8.sql
d. database/seed_quiz_html_level1.sql
5. **Jalankan:** nyalakan Apache dan MySQL, lalu buka `http://localhost/kodelio`.
   Alternatif tanpa Apache: `php -S localhost:8000`, lalu ubah `app_url` menjadi `http://localhost:8000`.
6. **Tes tanpa SMTP:** daftar akun, lalu verifikasi manual lewat phpMyAdmin:
   `UPDATE users SET email_verified_at=NOW() WHERE email='emailmu@contoh.com';`

## Alur kerja bareng lewat Git
1. Selalu ambil kode terbaru dulu: `git checkout main` lalu `git pull`.
2. Buat branch sendiri: `git checkout -b fitur-nama-singkat`.
3. Edit, lalu `git add .`, `git status` (pastikan `config/config.php` tidak muncul), dan `git commit -m "apa yang diubah"`.
4. Kirim branch: `git push -u origin fitur-nama-singkat`.
5. Di GitHub klik **Compare & pull request**, lalu **Create pull request**. Pemilik repo yang me-review dan merge.

Boleh di-push: semua kode, `database/schema.sql`, `database/seed.sql`, `config/config.example.php`, `composer.json`, `composer.lock`, `.gitignore`.
JANGAN di-push: `config/config.php`, `vendor/`, dump database berisi data asli.
- Teman yang clone: `composer install`, salin `config.example.php` jadi `config.php` lalu isi sendiri, import `schema.sql` lalu `seed.sql`.

`require_verification` di config: false = register langsung aktif tanpa email verifikasi. Fitur lupa password tetap butuh SMTP.
