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
| 4 Pemilihan jalur + placement test | Belum |
| 4–5 Peta jalur belajar (kategori → level → step, kunci berurutan, awan penutup) | Selesai, data dari DB. Pemilihan jalur resmi + placement test belum |
| 5 Dashboard lengkap | Baru ringkasan progres per jalur |
| 6–14 Materi, editor, mini-game, kuis, gamifikasi, riwayat, proyek, admin | Belum |

Keamanan yang sudah ada: password_hash, prepared statements, CSRF, token acak (hanya hash tersimpan, sekali pakai, kedaluwarsa), pesan reset yang tidak membocorkan email, escape output (XSS), folder `includes`, `config`, `database` diblokir lewat .htaccess (Apache).

`demo_mode` di config: true = tombol "Tandai selesai (demo)" di lesson.php aktif untuk menguji alur kunci. Matikan setelah materi, mini-game, dan kuis asli selesai.

## Kerja bareng lewat Git
- Boleh di-push: semua kode, `database/schema.sql`, `database/seed.sql`, `config/config.example.php`, `composer.json`, `composer.lock`, `.gitignore`.
- JANGAN di-push: `config/config.php`, `vendor/`, dump database berisi data asli.
- Teman yang clone: `composer install`, salin `config.example.php` jadi `config.php` lalu isi sendiri, import `schema.sql` lalu `seed.sql`.

`require_verification` di config: false = register langsung aktif tanpa email verifikasi. Fitur lupa password tetap butuh SMTP.
