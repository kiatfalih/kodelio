<?php require __DIR__.'/includes/bootstrap.php'; require __DIR__.'/includes/app_top.php';
$u=require_login();$uid=(int)$u['id'];
$s=db()->prepare("(SELECT 'Step selesai' t,l.title,CONCAT('+',l.xp_reward,' XP') d,p.completed_at ts FROM lesson_progress p JOIN lessons l ON l.id=p.lesson_id WHERE p.user_id=? AND p.status='completed')
UNION ALL (SELECT 'Mini game',l.title,CONCAT('Skor ',g.score,'% · +',g.xp_earned,' XP'),g.played_at FROM game_results g JOIN lessons l ON l.id=g.lesson_id WHERE g.user_id=?)
UNION ALL (SELECT 'Final quiz',l.title,CONCAT('Nilai ',q.score,'%',IF(q.passed,' (lulus)',' (belum lulus)')),q.taken_at FROM quiz_results q JOIN lessons l ON l.id=q.lesson_id WHERE q.user_id=?)
UNION ALL (SELECT 'Daily challenge','Tantangan harian',CONCAT('+',cp.xp_earned,' XP'),cp.completed_at FROM challenge_progress cp WHERE cp.user_id=?)
UNION ALL (SELECT 'Achievement',a.name,CONCAT('+',a.xp_bonus,' XP bonus'),ua.earned_at FROM user_achievements ua JOIN achievements a ON a.id=ua.achievement_id WHERE ua.user_id=?)
ORDER BY ts DESC LIMIT 100");$s->execute([$uid,$uid,$uid,$uid,$uid]);$H=$s->fetchAll();
$xp=(int)db()->query('SELECT COALESCE((SELECT xp FROM user_points WHERE user_id='.$uid.'),0)')->fetchColumn();
$ic=['Step selesai'=>'bi-check-circle-fill text-success','Mini game'=>'bi-controller text-primary','Final quiz'=>'bi-trophy-fill text-warning','Daily challenge'=>'bi-fire text-danger','Achievement'=>'bi-award-fill text-warning'];
app_head('Riwayat Belajar','hist');?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3"><div><h1 class="fw-bold mb-0"><i class="bi bi-clock-history text-primary"></i> Riwayat belajar</h1><div class="text-muted">Total XP: <b><?=$xp?></b></div></div><a class="btn btn-outline-k" href="review.php"><i class="bi bi-arrow-repeat"></i> Review soal yang salah</a></div>
<div class="card-k p-2"><?php foreach($H as $h):?><div class="d-flex align-items-center gap-3 p-2 border-bottom"><i class="bi <?=$ic[$h['t']]??'bi-dot'?> fs-4"></i><div class="flex-fill"><div class="fw-medium"><?=e($h['title'])?></div><small class="text-muted"><?=e($h['t'])?> · <?=e($h['d'])?></small></div><small class="text-muted text-nowrap"><?=date('d M, H:i',strtotime($h['ts']))?></small></div><?php endforeach;?>
<?php if(!$H):?><div class="p-3 text-muted">Belum ada aktivitas. Mulai dari satu step!</div><?php endif;?></div><?php app_foot();
