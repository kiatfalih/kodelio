<?php require __DIR__.'/includes/bootstrap.php'; require __DIR__.'/includes/app_top.php';
$u=require_login();$uid=(int)$u['id'];$pdo=db();
$s=$pdo->prepare('SELECT lesson_id,wrong_details FROM quiz_results WHERE user_id=? ORDER BY id');$s->execute([$uid]);$ever=[];$latest=[];
foreach($s->fetchAll() as $r){$w=json_decode((string)$r['wrong_details'],true)?:[];$ids=[];foreach($w as $x){$qid=(int)$x['question_id'];$ids[]=$qid;$ever[$qid]=['lesson'=>(int)$r['lesson_id'],'chosen'=>(int)($x['chosen']??0)];}$latest[(int)$r['lesson_id']]=$ids;}
$Q=[];$ans=[];
if($ever){$in=implode(',',array_map('intval',array_keys($ever)));$Q=$pdo->query("SELECT * FROM questions WHERE id IN ($in) ORDER BY id DESC")->fetchAll();
 foreach($pdo->query("SELECT * FROM answers WHERE question_id IN ($in)") as $a)$ans[$a['question_id']][$a['id']]=$a;}
app_head('Review Mistakes','hist');?>
<div class="d-flex justify-content-between align-items-center mb-3"><h1 class="fw-bold mb-0"><i class="bi bi-arrow-repeat text-danger"></i> Review Mistakes</h1><a class="btn btn-outline-k" href="history.php"><i class="bi bi-arrow-left"></i> Riwayat</a></div>
<p class="text-muted">Soal final quiz yang pernah kamu jawab salah. Kesalahan di mini game belum disimpan.</p>
<?php foreach($Q as $q):$qid=(int)$q['id'];$fixed=!in_array($qid,$latest[$ever[$qid]['lesson']]??[],true);$A=$ans[$qid]??[];$ch=$A[$ever[$qid]['chosen']]['answer_text']??'(tidak menjawab)';$ok='';foreach($A as $a)if($a['is_correct'])$ok=$a['answer_text'];?>
<div class="card-k p-4 mb-3"><?php if($fixed):?><span class="badge text-bg-success mb-2">Sudah benar di percobaan terbaru</span><?php endif;?>
<p class="fw-semibold mb-2"><?=e($q['prompt'])?></p><?php if($q['code_example']):?><pre class="code-ex"><?=e($q['code_example'])?></pre><?php endif;?>
<div class="text-danger"><i class="bi bi-x-circle-fill"></i> Jawabanmu: <?=e($ch)?></div><div class="text-success"><i class="bi bi-check-circle-fill"></i> Jawaban benar: <b><?=e($ok)?></b></div><?php if($q['explanation']):?><p class="text-muted mt-2 mb-0"><?=e($q['explanation'])?></p><?php endif;?></div><?php endforeach;?>
<?php if(!$Q):?><div class="card-k p-4 text-muted">Belum ada soal yang salah. Mantap!</div><?php endif;?><?php app_foot();
