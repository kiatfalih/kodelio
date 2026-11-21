<?php require __DIR__.'/includes/bootstrap.php'; require __DIR__.'/includes/learning.php'; require __DIR__.'/includes/progress.php';
$u=require_login();$uid=(int)$u['id'];$id=(int)($_GET['id']??$_POST['id']??0);
$L=find_lesson($uid,$id);
if(!$L){http_response_code(404);exit('Step tidak ditemukan.');}
if($L['state']==='lock'){flash('warning','Step itu masih terkunci. Selesaikan step sebelumnya dulu.');redirect('path.php?p='.$L['slug']);}
$demo=!empty($GLOBALS['cfg']['demo_mode']);$isDone=$L['state']==='done';
if($_SERVER['REQUEST_METHOD']==='POST'&&$demo&&!$isDone&&in_array($L['type'],['minigame','quiz'],true)){ // hanya sampai mini game & kuis asli ada
 csrf_check();$xp=complete_lesson(db(),$uid,$L);flash('success','Step selesai! +'.$xp.' XP');redirect('lesson.php?id='.$id);
}
if(!$isDone)db()->prepare("INSERT IGNORE INTO lesson_progress(user_id,lesson_id,status) VALUES(?,?,'in_progress')")->execute([$uid,$id]);
$sc=db()->prepare('SELECT starter_code FROM lessons WHERE id=?');$sc->execute([$id]);$starter=(string)$sc->fetchColumn();
$flat=flat_steps($uid,$L['slug']);$k=array_search($id,$flat,true);$prev=$k>0?$flat[$k-1]:null;$next=($k!==false&&isset($flat[$k+1]))?$flat[$k+1]:null;
$lvl=null;foreach(path_tree($uid,$L['slug']) as $m)foreach($m['levels'] as $l)foreach($l['steps'] as $s)if($s['id']===$id)$lvl=$l;
$nd=0;foreach($lvl['steps'] as $s)if($s['state']==='done')$nd++;$tot=count($lvl['steps']);$pct=$tot?round($nd/$tot*100):0;
$f=flash();$isPractice=$L['type']==='practice';$isAuto=in_array($L['type'],['learn','understand','practice'],true);
$c=(string)$L['content'];
?><!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e($L['title'])?> · Kodelio</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"><link href="assets/css/style.css" rel="stylesheet"><link href="assets/css/lesson.css" rel="stylesheet">
<?php if($isPractice):?><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/dracula.min.css"><?php endif;?></head><body>
<nav class="navbar k-nav"><div class="container"><a class="brand" href="dashboard.php"><i class="bi bi-braces"></i> Kodelio</a><a class="btn btn-outline-k btn-sm" href="path.php?p=<?=e($L['slug'])?>&open=<?=$L['mid']?>#s<?=$id?>"><i class="bi bi-map"></i> Peta belajar</a></div></nav>
<main class="container py-4" style="max-width:1120px"><?php if($f):?><div class="alert alert-<?=e($f[0])?>"><?=e($f[1])?></div><?php endif;?>
<div class="row g-4">
<aside class="col-lg-3"><div class="card-k p-3 ls-side"><div class="small text-muted"><?=e($lvl['title'])?></div>
<div class="meter my-2"><i style="width:<?=$pct?>%"></i></div><div class="small mb-3"><?=$nd?>/<?=$tot?> step selesai</div>
<?php foreach($lvl['steps'] as $i=>$s):$on=$s['id']===$id;?>
<?php if($s['state']==='lock'):?><div class="ls-item lock"><i class="bi bi-lock-fill"></i> <?=$i+1?>. <?=e(TYPE_LABEL[$s['type']])?></div>
<?php else:?><a class="ls-item <?=$on?'on':''?>" href="lesson.php?id=<?=$s['id']?>"><i class="bi <?=$s['state']==='done'?'bi-check-circle-fill text-success':TYPE_ICON[$s['type']]?>"></i> <?=$i+1?>. <?=e(TYPE_LABEL[$s['type']])?></a><?php endif;?>
<?php endforeach;?></div></aside>
<section class="col-lg-9"><div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3"><div><span class="pill mb-2"><i class="bi <?=TYPE_ICON[$L['type']]?>"></i> <?=e(TYPE_LABEL[$L['type']])?></span><h1 class="h3 fw-bold mb-0"><?=e($L['title'])?></h1></div><span class="xp-badge"><i class="bi bi-lightning-charge-fill"></i> +<?=(int)$L['xp_reward']?> XP</span></div>
<div class="card-k p-4 mb-3 ls-body"><?=$c===''?'<p class="mb-0 text-muted">Isi materi untuk step ini belum dibuat.</p>':(preg_match('/<[a-z][\s\S]*>/i',$c)?$c:nl2br(e($c)))?></div>
<?php if($isPractice):?><div class="row g-3 mb-3"><div class="col-lg-6"><textarea id="code" spellcheck="false"><?=e($starter)?></textarea><div class="mt-2 d-flex gap-2"><button type="button" id="run" class="btn btn-k"><i class="bi bi-play-fill"></i> Run Code</button><button type="button" id="reset" class="btn btn-outline-k"><i class="bi bi-arrow-counterclockwise"></i> Reset</button></div><div class="small text-muted mt-1">Ctrl+Enter untuk menjalankan</div></div>
<div class="col-lg-6"><iframe id="pv" sandbox="allow-scripts" title="Live preview"></iframe></div></div><?php endif;?>
<?php if(!$isAuto):?><div class="alert alert-info">Mini game dan kuis dibuat di tahap 8–9, jadi step ini belum bisa diselesaikan secara nyata.</div>
<?php if($demo&&!$isDone):?><form method="post"><?=csrf_field()?><input type="hidden" name="id" value="<?=$id?>"><button class="btn btn-outline-k btn-sm">Tandai selesai (demo)</button></form><?php endif;?><?php endif;?>
<div id="fb"><?php if($isDone):?><div class="alert alert-success mb-0">Step ini sudah selesai.</div><?php endif;?></div>
<div class="d-flex justify-content-between align-items-center mt-3 gap-2"><?php if($prev):?><a class="btn btn-outline-k" href="lesson.php?id=<?=$prev?>"><i class="bi bi-arrow-left"></i> Previous</a><?php else:?><span></span><?php endif;?>
<div class="d-flex gap-2"><?php if($isAuto):?><button type="button" id="done" class="btn btn-k" <?=$isDone?'disabled':''?>><?=$isDone?'Selesai':'Complete Step'?></button><?php endif;?>
<a id="next" class="btn btn-k <?=$isDone?'':'disabled'?>" href="<?=$isDone?($next?'lesson.php?id='.$next:'path.php?p='.e($L['slug'])):'#'?>">Next <i class="bi bi-arrow-right"></i></a></div></div>
</section></div></main>
<script>window.LS={id:<?=$id?>,slug:<?=json_encode($L['slug'])?>,csrf:<?=json_encode(csrf())?>};</script>
<?php if($isPractice):?><script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/xml/xml.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/javascript/javascript.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/css/css.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/htmlmixed/htmlmixed.min.js"></script><?php endif;?>
<script src="assets/js/lesson.js"></script></body></html>
