<?php /** @var string $title */ $f=flash(); ?>
<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=e($title)?> · Kodelio</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet"></head>
<body class="auth-body"><main class="auth-wrap container"><div class="auth-card row g-0">
<aside class="col-lg-5 auth-side d-none d-lg-flex"><a class="brand text-white" href="index.php"><i class="bi bi-braces"></i> Kodelio</a>
<div><h2>Belajar coding, level demi level.</h2><p>Kumpulkan XP, jaga streak, dan buka materi berikutnya setelah menyelesaikan yang sebelumnya.</p></div>
<div class="chips"><span class="chip xp"><i class="bi bi-lightning-charge-fill"></i> +20 XP</span><span class="chip ok"><i class="bi bi-check2-circle"></i> Level selesai</span></div></aside>
<section class="col-lg-7 auth-main"><a class="brand d-lg-none mb-3 d-inline-block" href="index.php"><i class="bi bi-braces"></i> Kodelio</a>
<?php if($f):?><div class="alert alert-<?=e($f[0])?>" role="alert"><?=e($f[1])?></div><?php endif;?>
