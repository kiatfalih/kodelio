<?php require __DIR__.'/includes/bootstrap.php'; require __DIR__.'/includes/learning.php';
$u=require_login();$uid=(int)$u['id'];
$slug=($_GET['p']??'beginner')==='intermediate'?'intermediate':'beginner';
db()->prepare('UPDATE users SET active_path_id=(SELECT id FROM learning_paths WHERE slug=?) WHERE id=?')->execute([$slug,$uid]);
$tree=path_tree($uid,$slug);$open=(int)($_GET['open']??0);$f=flash();
$XS=[-48,48,34,-34,14];
?><!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Jalur <?=e(ucfirst($slug))?> · Kodelio</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"><link href="assets/css/style.css" rel="stylesheet"><link href="assets/css/path.css" rel="stylesheet"></head><body>
<nav class="navbar sticky-top k-nav"><div class="container"><a class="brand" href="dashboard.php"><i class="bi bi-braces"></i> Kodelio</a>
<div class="pathsw"><a class="<?=$slug==='beginner'?'on':''?>" href="path.php?p=beginner">Beginner</a><a class="<?=$slug==='intermediate'?'on':''?>" href="path.php?p=intermediate">Intermediate</a></div>
<a class="btn btn-outline-k btn-sm" href="dashboard.php"><i class="bi bi-house"></i></a></div></nav>
<main class="pathpage" id="app" data-open="<?=$open?>">
<?php if($f):?><div class="alert alert-<?=e($f[0])?>"><?=e($f[1])?></div><?php endif;?>
<div id="home" class="kmods"><div class="mb-2"><h1 class="h3 fw-bold mb-1">Jalur <?=e(ucfirst($slug))?></h1><p class="text-muted mb-0">Pilih kategori untuk mulai. Kategori berikutnya terbuka setelah yang sebelumnya selesai.</p></div>
<?php foreach($tree as $m):[$d,$t]=tree_counts($m);$locked=$m['levels']?reset($m['levels'])['steps'][0]['state']==='lock':true;?>
<button class="kmod<?=$locked?' locked':''?><?=($d===$t)?' fin':''?>" data-m="<?=$m['id']?>" data-msg="Selesaikan kategori sebelumnya dulu.">
<span class="kico"><i class="bi <?=$locked?'bi-lock-fill':($d===$t?'bi-check-lg':'bi-play-fill')?>"></i></span>
<span class="kt"><b><?=e($m['name'])?></b><small><?=$locked?'Terkunci':"$d dari $t step selesai"?></small><span class="kbar"><i style="width:<?=$t?round($d/$t*100):0?>%"></i></span></span></button>
<?php endforeach;?></div>
<div id="flow" hidden><button class="btn btn-link px-0 mb-2" id="toHome"><i class="bi bi-chevron-left"></i> Semua kategori</button>
<?php foreach($tree as $m):?><section class="msec" id="m<?=$m['id']?>"><div class="mhead"><?=e($m['name'])?></div>
<?php foreach($m['levels'] as $l):$lock=$l['steps'][0]['state']==='lock';?>
<article class="lsec"><div class="lban<?=$lock?' locked':''?>"><small>Level <?=$l['no']?></small><h2><?=e($l['title'])?></h2><p><?=e($l['summary'])?></p></div>
<div class="stages"><?php foreach($l['steps'] as $i=>$s):$q=$s['type']==='quiz';$lab=TYPE_LABEL[$s['type']];?>
<div class="st" style="--x:<?=$q?0:$XS[$i%5]?>px"><?php if($s['state']==='cur'):?><span class="go"><?=$q?'Ujian':'Mulai'?></span><?php endif;?>
<button class="kdot <?=$s['state']?><?=$q?' final':''?>" id="s<?=$s['id']?>" data-id="<?=$s['id']?>" data-state="<?=$s['state']?>" data-label="<?=e($lab)?>" data-xp="<?=$s['xp']?>" data-lvl="Level <?=$l['no']?> · <?=e($l['title'])?>" aria-label="<?=e($lab)?>, <?=$s['state']==='done'?'selesai':($s['state']==='cur'?'sedang dikerjakan':'terkunci')?>"><i class="bi <?=$s['state']==='done'?'bi-check-lg':($s['state']==='lock'?'bi-lock-fill':TYPE_ICON[$s['type']])?>"></i></button>
<span class="lbl"><?=e($lab)?></span></div><?php endforeach;?>
<?php if($lock):?><div class="cloud"><span class="cloudmsg"><i class="bi bi-lock-fill"></i> Selesaikan level sebelumnya dulu</span></div><?php endif;?></div></article>
<?php endforeach;?></section><?php endforeach;?></div></main>
<dialog id="dlg"><h2 class="h5 fw-bold" id="dT"></h2><p class="text-muted" id="dS"></p><div class="d-flex gap-2 justify-content-end"><button class="btn btn-outline-k" id="dX">Tutup</button><a class="btn btn-k" id="dGo" href="#">Mulai step</a></div></dialog>
<div class="ktoast" id="toast" role="status"></div>
<script src="assets/js/path.js"></script></body></html>
