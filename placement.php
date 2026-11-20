<?php require __DIR__.'/includes/bootstrap.php';
$u=require_login();$uid=(int)$u['id'];$title='Tes Kemampuan Awal';
// [pertanyaan, [pilihan], indeks jawaban benar]
$Q=[['Properti CSS untuk membuat layout dua dimensi (baris dan kolom) adalah...',['display: grid','float: left','text-align: center','position: static'],0],
['Hasil dari [1,2,3].map(x => x*2) adalah...',['[1,2,3]','[2,4,6]','6','undefined'],1],
['Function yang diberi kata kunci async selalu mengembalikan...',['String','Boolean','Promise','Array'],2],
['JOIN pada SQL digunakan untuk...',['Menghapus tabel','Menggabungkan baris dari dua tabel berdasarkan kolom terkait','Membuat database baru','Mengurutkan data'],1],
['Sintaks const {nama} = user; disebut...',['Destructuring','Looping','Hoisting','Casting'],0],
['Kode status HTTP untuk data yang tidak ditemukan adalah...',['200','301','404','500'],2]];
if($_SERVER['REQUEST_METHOD']==='POST'){
 csrf_check();$score=0;$ans=[];
 foreach($Q as $i=>$q){$a=(int)($_POST['q'.$i]??-1);$ans[]=$a;if($a===$q[2])$score++;}
 $slug=$score>=4?'intermediate':'beginner';
 $s=db()->prepare('SELECT id FROM learning_paths WHERE slug=?');$s->execute([$slug]);$pid=(int)$s->fetchColumn();
 db()->prepare('INSERT INTO placement_results(user_id,score,recommended_path_id,answers) VALUES(?,?,?,?)')->execute([$uid,$score,$pid?:null,json_encode($ans)]);
 $_SESSION['rec']=$slug;$_SESSION['rec_score']=[$score,count($Q)];redirect('choose-path.php');
}
?><!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e($title)?> · Kodelio</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"><link href="assets/css/style.css" rel="stylesheet"><link href="assets/css/onboarding.css" rel="stylesheet"></head><body class="ob-body">
<nav class="navbar k-nav"><div class="container"><a class="brand" href="dashboard.php"><i class="bi bi-braces"></i> Kodelio</a><a class="btn btn-outline-k btn-sm" href="choose-path.php"><i class="bi bi-x-lg"></i> Lewati</a></div></nav>
<main class="ob-wrap narrow">
<div class="ob-head"><span class="pill"><i class="bi bi-compass"></i> Tes kemampuan awal</span><h1>Seberapa jauh kamu sudah melangkah?</h1><p class="text-muted mb-0">6 soal singkat untuk merekomendasikan jalur yang pas. Tebakan boleh!</p></div>
<form method="post" id="ptForm"><?=csrf_field()?>
<div class="pt-top"><span id="ptLab">Soal 1 dari <?=count($Q)?></span><span class="text-muted small"><i class="bi bi-lightning-charge-fill" style="color:var(--k-xp)"></i> Tanpa batas waktu</span></div>
<div class="pt-bar"><i id="ptBar"></i></div>
<div class="pt-card">
<?php foreach($Q as $i=>$q):?><div class="pt-qw"><div class="pt-num">Pertanyaan <?=$i+1?></div><p class="pt-q"><?=e($q[0])?></p>
<?php foreach($q[1] as $j=>$o):?><label class="pt-opt"><input type="radio" name="q<?=$i?>" value="<?=$j?>" required><span class="k"><?=chr(65+$j)?></span><span><?=e($o)?></span></label><?php endforeach;?></div><?php endforeach;?>
<div class="pt-nav"><button type="button" class="btn btn-outline-k" id="ptBack"><i class="bi bi-arrow-left"></i> Kembali</button>
<button type="button" class="btn btn-k" id="ptNext" disabled>Lanjut <i class="bi bi-arrow-right"></i></button><button class="btn btn-k" id="ptGo" hidden disabled><i class="bi bi-stars"></i> Lihat hasil</button></div></div>
<p class="pt-hint d-none d-md-block">Tips: tekan <b>A–D</b> atau <b>1–4</b> untuk memilih, <b>Enter</b> untuk lanjut.</p></form></main>
<script src="assets/js/placement.js"></script></body></html>
