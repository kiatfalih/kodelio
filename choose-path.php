<?php require __DIR__.'/includes/bootstrap.php'; require __DIR__.'/includes/learning.php';
$u=require_login();$uid=(int)$u['id'];
if($_SERVER['REQUEST_METHOD']==='POST'){
 csrf_check();$slug=($_POST['path']??'')==='intermediate'?'intermediate':'beginner';
 db()->prepare('UPDATE users SET active_path_id=(SELECT id FROM learning_paths WHERE slug=?) WHERE id=?')->execute([$slug,$uid]);
 redirect('path.php?p='.$slug);
}
$rec=$_SESSION['rec']??null;$sc=$_SESSION['rec_score']??null;unset($_SESSION['rec'],$_SESSION['rec_score']);
if(!in_array($rec,['beginner','intermediate'],true))$rec=null;
$f=flash();$title='Pilih Jalur';
$P=['beginner'=>['Beginner','bi-flower1','','Mulai dari nol, tanpa pengalaman coding.',['HTML','CSS','JavaScript']],
    'intermediate'=>['Intermediate','bi-rocket-takeoff','mid','Sudah paham dasar web? Saatnya bikin aplikasi nyata.',['JS Lanjutan','MySQL','Backend','API','Proyek Web']]];
?><!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e($title)?> · Kodelio</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"><link href="assets/css/style.css" rel="stylesheet"><link href="assets/css/onboarding.css" rel="stylesheet"></head><body class="ob-body">
<nav class="navbar k-nav"><div class="container"><a class="brand" href="dashboard.php"><i class="bi bi-braces"></i> Kodelio</a><a class="btn btn-outline-k btn-sm" href="dashboard.php"><i class="bi bi-house"></i></a></div></nav>
<main class="ob-wrap">
<div class="ob-head"><span class="pill"><i class="bi bi-signpost-split"></i> Pilih jalur belajar</span><h1>Mau mulai dari mana?</h1><p class="text-muted mb-0">Kamu bebas memilih dan boleh pindah kapan saja. Progres tiap jalur disimpan terpisah.</p></div>
<?php if($f):?><div class="alert alert-<?=e($f[0])?>"><?=e($f[1])?></div><?php endif;?>
<?php if($rec&&$sc):?><div class="ob-result"><div class="ring" style="--p:<?=round($sc[0]/$sc[1]*100)?>"><span><?=(int)$sc[0]?>/<?=(int)$sc[1]?></span></div>
<div><div class="fw-bold fs-5">Hasil tesmu sudah siap!</div><div>Kami menyarankan jalur <b><?=e($P[$rec][0])?></b>. Kamu tetap bebas memilih jalur lain.</div></div></div><?php endif;?>
<div class="row g-4"><?php foreach($P as $slug=>$p):$tree=path_tree($uid,$slug);$D=0;$T=0;$L=0;foreach($tree as $m){[$x,$y]=tree_counts($m);$D+=$x;$T+=$y;$L+=count($m['levels']);}$pct=$T?round($D/$T*100):0;?>
<div class="col-md-6"><form method="post" class="pc <?=$p[2]?><?=$rec===$slug?' rec':''?>"><?=csrf_field()?><input type="hidden" name="path" value="<?=$slug?>">
<?php if($rec===$slug):?><span class="rib">Disarankan</span><?php endif;?>
<div class="ic"><i class="bi <?=$p[1]?>"></i></div><h2><?=e($p[0])?></h2><p class="text-muted mb-2"><?=e($p[3])?></p>
<div><?php foreach($p[4] as $t):?><span class="tag"><?=e($t)?></span><?php endforeach;?></div>
<div class="stats"><div><b><?=count($tree)?></b><small>Kategori</small></div><div><b><?=$L?></b><small>Level</small></div><div><b><?=$T?></b><small>Step</small></div></div>
<div class="mt-auto"><?php if($D>0):?><div class="d-flex justify-content-between small mb-1"><span class="text-muted">Progresmu</span><b><?=$pct?>%</b></div><div class="pbar mb-3"><i style="width:<?=$pct?>%"></i></div><?php endif;?>
<button class="btn btn-k w-100"><?=$D>0?'Lanjutkan':'Mulai'?> <?=e($p[0])?> <i class="bi bi-arrow-right"></i></button></div></form></div><?php endforeach;?></div>
<div class="ob-test"><div><b><i class="bi bi-compass text-primary"></i> <?=$rec?'Mau mengulang tesnya?':'Belum yakin pilih yang mana?'?></b><div class="text-muted small">Tes singkat 6 soal, tanpa batas waktu.</div></div><a class="btn btn-outline-k" href="placement.php"><?=$rec?'Ulangi tes':'Ikuti tes kemampuan awal'?></a></div>
</main></body></html>
