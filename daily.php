<?php require __DIR__.'/includes/bootstrap.php'; require __DIR__.'/includes/learning.php'; require __DIR__.'/includes/progress.php'; require __DIR__.'/includes/app_top.php';
$u=require_login();$uid=(int)$u['id'];$pdo=db();
$ch=$pdo->query('SELECT * FROM daily_challenges WHERE challenge_date=CURDATE() LIMIT 1')->fetch();
if(!$ch){ // buat tantangan hari ini dari bank soal pilihan ganda (soal dipilih deterministik per hari)
 $qid=$pdo->query("SELECT id FROM questions WHERE type='multiple_choice' ORDER BY MD5(CONCAT(CURDATE(),id)) LIMIT 1")->fetchColumn();
 if($qid){$pdo->prepare("INSERT IGNORE INTO daily_challenges(challenge_date,title,description,question_id,xp_reward) VALUES(CURDATE(),'Tantangan Harian','Jawab satu soal hari ini untuk bonus XP.',?,20)")->execute([$qid]);
  $ch=$pdo->query('SELECT * FROM daily_challenges WHERE challenge_date=CURDATE() LIMIT 1')->fetch();}
}
$Q=null;$A=[];$done=false;$msg=null;
if($ch&&$ch['question_id']){
 $s=$pdo->prepare('SELECT * FROM questions WHERE id=?');$s->execute([$ch['question_id']]);$Q=$s->fetch();
 $s=$pdo->prepare('SELECT * FROM answers WHERE question_id=?');$s->execute([$ch['question_id']]);$A=$s->fetchAll();
 usort($A,fn($a,$b)=>strcmp(md5(date('Ymd').$a['id']),md5(date('Ymd').$b['id'])));
 $s=$pdo->prepare('SELECT 1 FROM challenge_progress WHERE user_id=? AND challenge_id=?');$s->execute([$uid,$ch['id']]);$done=(bool)$s->fetchColumn();
}
if($Q&&!$done&&$_SERVER['REQUEST_METHOD']==='POST'){
 csrf_check();$pick=null;foreach($A as $a)if((int)$a['id']===(int)($_POST['a']??0))$pick=$a;
 if(!$pick)$msg=['warning','Pilih salah satu jawaban dulu.'];
 elseif($pick['is_correct']){
  $pdo->beginTransaction();$r=$pdo->prepare('INSERT IGNORE INTO challenge_progress(user_id,challenge_id,xp_earned) VALUES(?,?,?)');$r->execute([$uid,$ch['id'],$ch['xp_reward']]);
  if($r->rowCount()){add_xp($pdo,$uid,(int)$ch['xp_reward']);bump_streak($pdo,$uid);}$pdo->commit();
  $done=true;$msg=['success','Benar! +'.(int)$ch['xp_reward'].' XP. '.($Q['explanation']??'')];
 }else $msg=['danger','Belum tepat. '.(($pick['feedback']?:$Q['hint'])??'').' Coba lagi!'];
}
app_head('Daily Challenge','daily');?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3"><div><h1 class="fw-bold mb-0"><i class="bi bi-fire text-warning"></i> Daily Challenge</h1><div class="text-muted"><?=date('d M Y')?> · satu soal per hari</div></div><span class="xp-badge" style="background:#fff4dc;color:#a86a00;font-weight:700;border-radius:99px;padding:.4rem .9rem">+<?=(int)($ch['xp_reward']??20)?> XP</span></div>
<?php if(!$Q):?><div class="card-k p-4 text-muted">Belum ada soal yang bisa dijadikan tantangan hari ini. Coba lagi nanti.</div>
<?php else:?><div class="card-k p-4"><?php if($done&&!$msg):?><div class="alert alert-success mb-0"><i class="bi bi-check-circle-fill"></i> Tantangan hari ini sudah selesai. Kembali lagi besok!</div>
<?php else:?><form method="post"><?=csrf_field()?><p class="fw-semibold fs-5"><?=e($Q['prompt'])?></p><?php if($Q['code_example']):?><pre class="code-ex"><?=e($Q['code_example'])?></pre><?php endif;?>
<?php foreach($A as $a):?><label class="dq"><input type="radio" name="a" value="<?=(int)$a['id']?>" <?=$done?'disabled':''?>> <?=e($a['answer_text'])?></label><?php endforeach;?>
<?php if($msg):?><div class="alert alert-<?=$msg[0]?>"><?=e($msg[1])?></div><?php endif;?><?php if(!$done):?><button class="btn btn-k">Periksa jawaban</button><?php endif;?></form><?php endif;?></div><?php endif;?>
<?php app_foot();
