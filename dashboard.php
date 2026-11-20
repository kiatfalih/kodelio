<?php require __DIR__.'/includes/bootstrap.php'; require __DIR__.'/includes/learning.php'; $u=require_login(); $title='Dashboard'; $f=flash();
$ap=db()->prepare('SELECT lp.name FROM users u JOIN learning_paths lp ON lp.id=u.active_path_id WHERE u.id=?');$ap->execute([$u['id']]);$apName=$ap->fetchColumn();if(!$apName)redirect('choose-path.php');
?><!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Dashboard · Kodelio</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"><link href="assets/css/style.css" rel="stylesheet"><link href="assets/css/onboarding.css" rel="stylesheet"></head><body>
<nav class="navbar k-nav"><div class="container"><a class="brand" href="index.php"><i class="bi bi-braces"></i> Kodelio</a>
<form method="post" action="logout.php"><?=csrf_field()?><button class="btn btn-outline-k btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</button></form></div></nav>
<main class="container py-5"><h1 class="fw-bold">Halo, <?=e($u['name'])?>! 👋</h1><p class="text-muted mb-3">Pilih jalur belajarmu. Fitur lain dibangun bertahap.</p>
<div class="dash-bar"><span class="pill"><i class="bi bi-bookmark-star-fill"></i> Jalur aktif: <b><?=e($apName)?></b></span>
<a class="btn btn-outline-k" href="choose-path.php"><i class="bi bi-arrow-left-right"></i> Ganti jalur</a>
<a class="btn btn-k" href="placement.php"><i class="bi bi-compass"></i> Tes kemampuan awal</a></div>
<div class="row g-3 mb-4"><?php foreach(['beginner'=>['Beginner','bi-flower1','HTML, CSS, JavaScript'],'intermediate'=>['Intermediate','bi-rocket-takeoff','JS lanjutan, MySQL, Backend, API']] as $slug=>$p):
 $tr=path_tree((int)$u['id'],$slug);$D=0;$T=0;foreach($tr as $m){[$x,$y]=tree_counts($m);$D+=$x;$T+=$y;}?>
<div class="col-md-6"><a class="card-k hover p-4 d-block text-decoration-none text-reset h-100" href="path.php?p=<?=$slug?>"><div class="d-flex align-items-center gap-3 mb-3"><div class="ico-box"><i class="bi <?=$p[1]?>"></i></div><div><b class="fs-5"><?=$p[0]?></b><div class="small text-muted"><?=$p[2]?></div></div></div>
<div class="progress" style="height:8px"><div class="progress-bar bg-success" style="width:<?=$T?round($D/$T*100):0?>%"></div></div><small class="text-muted"><?=$D?> dari <?=$T?> step selesai</small></a></div><?php endforeach;?></div>
<?php foreach([['XP level, streak, achievement, leaderboard','Tahap 10'],['Materi, editor kode, mini-game, kuis','Tahap 6–9'],['Riwayat, final project, admin','Tahap 11–13']] as $s):?><div class="card-k p-3 mb-2 d-flex justify-content-between align-items-center"><span><?=e($s[0])?></span><span class="badge text-bg-secondary">Belum tersedia · <?=e($s[1])?></span></div><?php endforeach;?></main>
</body></html>
