<?php require __DIR__.'/includes/bootstrap.php'; $u=current_user(); ?>
<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Kodelio · Belajar Coding Interaktif</title><meta name="description" content="Belajar pemrograman web dari nol lewat materi interaktif, tantangan coding, dan mini-game.">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet"></head><body>
<nav class="navbar navbar-expand-lg sticky-top k-nav"><div class="container">
<a class="brand" href="#beranda"><i class="bi bi-braces"></i> Kodelio</a>
<button class="navbar-toggler border-0" data-bs-toggle="collapse" data-bs-target="#nv" aria-label="Menu"><i class="bi bi-list fs-2"></i></button>
<div class="collapse navbar-collapse" id="nv"><ul class="navbar-nav mx-auto">
<li class="nav-item"><a class="nav-link" href="#beranda">Beranda</a></li><li class="nav-item"><a class="nav-link" href="#tentang">Tentang Kami</a></li>
<li class="nav-item"><a class="nav-link" href="#fitur">Fitur</a></li><li class="nav-item"><a class="nav-link" href="#jalur">Jalur Belajar</a></li></ul>
<div class="d-flex gap-2 pb-2 pb-lg-0"><?php if($u):?><a class="btn btn-k" href="dashboard.php">Dashboard</a><?php else:?><a class="btn btn-outline-k" href="login.php">Login</a><a class="btn btn-k" href="register.php">Register</a><?php endif;?></div></div></div></nav>

<header class="hero" id="beranda"><div class="container"><div class="row align-items-center g-5">
<div class="col-lg-6"><span class="pill mb-3"><i class="bi bi-rocket-takeoff"></i> Platform belajar coding interaktif</span>
<h1>Belajar Coding Tidak Harus <span>Membosankan!</span></h1>
<p class="lead text-muted my-3">Mulai perjalanan belajar pemrograman dari nol melalui materi interaktif, tantangan coding, dan mini-game yang menyenangkan.</p>
<div class="d-flex flex-wrap gap-2"><a class="btn btn-k btn-lg" href="register.php">Mulai Belajar</a><a class="btn btn-outline-k btn-lg" href="#jalur">Lihat Jalur Belajar</a></div></div>
<div class="col-lg-6"><div class="visual"><div class="code-win"><div class="dots"><i></i><i></i><i></i></div>
<div><span class="cm">&lt;!-- Level 1 · HTML Introduction --&gt;</span></div>
<div><span class="t">&lt;h1&gt;</span>Halo, Dunia!<span class="t">&lt;/h1&gt;</span></div>
<div><span class="t">&lt;p</span> <span class="a">class</span>=<span class="s">"intro"</span><span class="t">&gt;</span>Ini kode pertamaku<span class="t">&lt;/p&gt;</span></div>
<div><span class="t">&lt;a</span> <span class="a">href</span>=<span class="s">"#"</span><span class="t">&gt;</span>Lanjut<span class="t">&lt;/a&gt;</span></div></div>
<span class="chip xp c1"><i class="bi bi-lightning-charge-fill"></i> +20 XP</span><span class="chip fire c2"><i class="bi bi-fire"></i> Streak 7 hari</span><span class="chip ok c3"><i class="bi bi-check-circle-fill"></i> Level selesai!</span></div></div></div></div></header>

<section class="section" id="tentang"><div class="container"><div class="row align-items-center g-5">
<div class="col-lg-5 reveal"><p class="eyebrow">Tentang Kodelio</p><h2>Belajar web programming seperti bermain game</h2></div>
<div class="col-lg-7 reveal"><p class="text-muted fs-5">Kodelio membantu kamu belajar coding dari nol sampai tingkat menengah. Bukan sekadar membaca artikel: setiap level punya materi singkat, penjelasan kode, latihan langsung di editor, mini-game, dan kuis akhir. Kamu mendapat XP, menjaga streak, dan membuka level berikutnya sedikit demi sedikit.</p></div></div></div></section>

<section class="section alt" id="jalur"><div class="container"><div class="text-center mb-5 reveal"><p class="eyebrow">Jalur Belajar</p><h2>Pilih jalurmu, atau pelajari keduanya</h2></div>
<div class="row g-4 justify-content-center">
<div class="col-md-6 col-lg-5 reveal"><div class="card-k hover p-4 h-100"><div class="ico-box mb-3"><i class="bi bi-seedling"></i></div><h3 class="h4 fw-bold">Beginner</h3><p class="text-muted">Untuk yang belum pernah coding atau masih sangat dasar.</p>
<div class="mb-3"><span class="tag">HTML</span><span class="tag">CSS</span><span class="tag">JavaScript</span></div><p class="small text-muted mb-0"><i class="bi bi-diagram-3"></i> 3 kategori · 15 level · 75 step · proyek profil pribadi</p></div></div>
<div class="col-md-6 col-lg-5 reveal"><div class="card-k hover p-4 h-100"><div class="ico-box y mb-3"><i class="bi bi-rocket-takeoff"></i></div><h3 class="h4 fw-bold">Intermediate</h3><p class="text-muted">Untuk yang sudah paham dasar HTML, CSS, dan JavaScript.</p>
<div class="mb-3"><span class="tag">Advanced JS</span><span class="tag">MySQL</span><span class="tag">Backend</span><span class="tag">API</span><span class="tag">Web App</span></div><p class="small text-muted mb-0"><i class="bi bi-diagram-3"></i> 5 level · 25 step · proyek aplikasi web</p></div></div></div></div></section>

<section class="section" id="fitur"><div class="container"><div class="text-center mb-5 reveal"><p class="eyebrow">Fitur Unggulan</p><h2>Semua yang kamu butuhkan untuk konsisten belajar</h2></div>
<div class="row g-4">
<?php foreach([['bi-journal-richtext','','Materi Interaktif','Materi singkat dengan contoh dan highlight kode yang mudah dipahami.'],['bi-terminal','g','Latihan Coding','Ketik dan jalankan kode langsung, hasilnya tampil di live preview.'],['bi-controller','y','Mini-Game','Susun kode, cari bug, tebak output, dan kuis cepat.'],['bi-lightning-charge','y','XP & Level','Setiap aktivitas memberi XP untuk menaikkan level akunmu.'],['bi-trophy','','Achievement','Kumpulkan badge saat mencapai pencapaian tertentu.'],['bi-graph-up-arrow','g','Progres Belajar','Pantau progres, streak harian, dan riwayat belajarmu.']] as $f):?>
<div class="col-md-6 col-lg-4 reveal"><div class="card-k hover p-4 h-100"><div class="ico-box <?=$f[1]?> mb-3"><i class="bi <?=$f[0]?>"></i></div><h3 class="h5 fw-bold"><?=$f[2]?></h3><p class="text-muted mb-0"><?=$f[3]?></p></div></div><?php endforeach;?></div></div></section>

<section class="section alt"><div class="container"><div class="text-center mb-5 reveal"><p class="eyebrow">Cara Belajar</p><h2>Lima langkah dari daftar sampai proyek</h2></div>
<div class="row g-4 justify-content-center"><div class="col-lg-8">
<?php foreach(['Daftar & verifikasi email','Pilih jalur Beginner atau Intermediate','Selesaikan step: materi, kode, latihan, mini-game','Lulus final quiz (min. 70%) untuk membuka level berikutnya','Kerjakan proyek akhir dan dapatkan penilaian'] as $i=>$t):?>
<div class="card-k p-3 mb-3 d-flex align-items-center gap-3 reveal"><span class="step-n"><?=$i+1?></span><span class="fw-semibold"><?=$t?></span></div><?php endforeach;?></div></div></div></section>

<section class="section"><div class="container"><div class="cta text-center reveal"><h2 class="fw-bold">Siap menulis baris kode pertamamu?</h2><p class="mb-4 opacity-75">Gratis dan bisa dimulai dari nol.</p><a class="btn btn-light btn-lg" href="register.php">Mulai Belajar Sekarang</a></div></div></section>

<footer class="foot"><div class="container"><div class="row g-4"><div class="col-lg-5"><a class="brand mb-2 d-inline-flex" href="#beranda"><i class="bi bi-braces"></i> Kodelio</a><p class="mb-0">Platform belajar pemrograman web interaktif untuk pemula sampai tingkat menengah.</p></div>
<div class="col-6 col-lg-3"><h6 class="text-white">Navigasi</h6><ul class="list-unstyled"><li><a href="#tentang">Tentang Kami</a></li><li><a href="#fitur">Fitur</a></li><li><a href="#jalur">Jalur Belajar</a></li></ul></div>
<div class="col-6 col-lg-4"><h6 class="text-white">Akun</h6><ul class="list-unstyled"><li><a href="login.php">Login</a></li><li><a href="register.php">Register</a></li><li><a href="forgot.php">Lupa password</a></li></ul></div></div>
<hr class="border-secondary"><p class="small mb-0">© <?=date('Y')?> Kodelio. Seluruh hak cipta dilindungi.</p></div></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script><script src="assets/js/app.js"></script></body></html>
