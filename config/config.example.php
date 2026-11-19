<?php
// Salin file ini menjadi config/config.php lalu isi sesuai server kamu.
return [
 'app_url'=>'http://localhost/kodelio',
 'require_verification'=>true, // false = akun langsung aktif tanpa verifikasi email (cocok untuk tes lokal)
 'demo_mode'=>true, // true = tombol "Tandai selesai (demo)" aktif; matikan setelah konten asli (tahap 6-9) selesai
 'db'=>['host'=>'127.0.0.1','name'=>'kodelio','user'=>'root','pass'=>'','charset'=>'utf8mb4'],
 'smtp'=>['host'=>'smtp.example.com','port'=>587,'user'=>'','pass'=>'','secure'=>'tls','from'=>'no-reply@kodelio.test','from_name'=>'Kodelio'],
];
