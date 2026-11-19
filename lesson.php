<?php require __DIR__.'/includes/bootstrap.php'; require __DIR__.'/includes/learning.php';
$u=require_login();$uid=(int)$u['id'];$id=(int)($_GET['id']??$_POST['id']??0);
$L=find_lesson($uid,$id);
if(!$L){http_response_code(404);exit('Step tidak ditemukan.');}
if($L['state']==='lock'){flash('warning','Step itu masih terkunci. Selesaikan step sebelumnya dulu.');redirect('path.php?p='.$L['slug']);}
$demo=!empty($GLOBALS['cfg']['demo_mode']);
if($_SERVER['REQUEST_METHOD']==='POST'&&$demo&&$L['state']!=='done'){
 csrf_check();
 $pdo=db();$pdo->beginTransaction();
 $pdo->prepare("INSERT INTO lesson_progress(user_id,lesson_id,status,completed_at) VALUES(?,?, 'completed',NOW()) ON DUPLICATE KEY UPDATE status='completed',completed_at=NOW()")->execute([$uid,$id]);
 $pdo->prepare('INSERT INTO user_points(user_id,xp) VALUES(?,?) ON DUPLICATE KEY UPDATE xp=xp+VALUES(xp)')->execute([$uid,$L['xp_reward']]);
 $pdo->commit();
 flash('success','Step selesai! +'.$L['xp_reward'].' XP');
 redirect('path.php?p='.$L['slug'].'&open='.$L['mid'].'#s'.$id);
}
?><!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e($L['title'])?> · Kodelio</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"><link href="assets/css/style.css" rel="stylesheet"></head><body>
<nav class="navbar k-nav"><div class="container"><a class="brand" href="dashboard.php"><i class="bi bi-braces"></i> Kodelio</a><a class="btn btn-outline-k btn-sm" href="path.php?p=<?=e($L['slug'])?>&open=<?=$L['mid']?>#s<?=$id?>"><i class="bi bi-map"></i> Kembali ke peta</a></div></nav>
<main class="container py-5" style="max-width:720px"><span class="pill mb-2"><i class="bi <?=TYPE_ICON[$L['type']]?>"></i> <?=e(TYPE_LABEL[$L['type']])?> · +<?=$L['xp_reward']?> XP</span>
<h1 class="h3 fw-bold"><?=e($L['title'])?></h1><p class="text-muted"><?=e($L['ltitle'])?></p>
<div class="card-k p-4 mb-3"><?=$L['content']?nl2br(e($L['content'])):'<p class="mb-0 text-muted">Isi materi untuk step ini belum dibuat (dikerjakan di tahap 6–9: materi, editor kode, mini-game, dan kuis).</p>'?></div>
<?php if($L['state']==='done'):?><div class="alert alert-success">Step ini sudah selesai.</div>
<?php elseif($demo):?><div class="alert alert-warning"><b>Mode demo.</b> Tombol di bawah hanya untuk menguji alur kunci/buka peta belajar. Penilaian kuis (min. 70%) belum aktif.</div>
<form method="post"><?=csrf_field()?><input type="hidden" name="id" value="<?=$id?>"><button class="btn btn-k">Tandai selesai (demo)</button></form>
<?php else:?><div class="alert alert-info">Konten dan penilaian step ini belum tersedia.</div><?php endif;?></main></body></html>
