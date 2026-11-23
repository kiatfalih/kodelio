<?php require __DIR__.'/includes/bootstrap.php'; require __DIR__.'/includes/app_top.php';
$u=require_login();$me=(int)$u['id'];$pdo=db();$w=($_GET['t']??'w')==='a'?'a':'w';
if($w==='a'){$R=$pdo->query("SELECT u.id,u.name,up.xp FROM user_points up JOIN users u ON u.id=up.user_id WHERE u.role='user' ORDER BY up.xp DESC,u.id LIMIT 20")->fetchAll();}
else{$since=date('Y-m-d 00:00:00',strtotime('monday this week'));
 $s=$pdo->prepare("SELECT u.id,u.name,(SELECT COALESCE(SUM(l.xp_reward),0) FROM lesson_progress p JOIN lessons l ON l.id=p.lesson_id WHERE p.user_id=u.id AND p.status='completed' AND p.completed_at>=?)+(SELECT COALESCE(SUM(cp.xp_earned),0) FROM challenge_progress cp WHERE cp.user_id=u.id AND cp.completed_at>=?) xp FROM users u WHERE u.role='user' AND u.email_verified_at IS NOT NULL ORDER BY xp DESC,u.id LIMIT 20");
 $s->execute([$since,$since]);$R=$s->fetchAll();}
app_head('Leaderboard','lb');?>
<h1 class="fw-bold mb-3"><i class="bi bi-bar-chart text-primary"></i> Leaderboard</h1>
<div class="mb-3 d-flex gap-2"><a class="btn <?=$w==='w'?'btn-k':'btn-outline-k'?>" href="?t=w">Mingguan</a><a class="btn <?=$w==='a'?'btn-k':'btn-outline-k'?>" href="?t=a">Keseluruhan</a></div>
<div class="card-k p-2"><?php foreach($R as $i=>$r):?><div class="d-flex align-items-center gap-3 p-2 rounded-3 <?=(int)$r['id']===$me?'bg-light':''?>"><span class="rk <?=$i<3?'r'.($i+1):''?>"><?=$i+1?></span><span class="flex-fill fw-medium"><?=e($r['name'])?><?=(int)$r['id']===$me?' <span class="badge text-bg-primary">kamu</span>':''?></span><b><?=(int)$r['xp']?> XP</b></div><?php endforeach;?>
<?php if(!$R):?><div class="p-3 text-muted">Belum ada data.</div><?php endif;?></div>
<p class="text-muted small mt-2"><?=$w==='w'?'Mingguan dihitung sejak Senin (step dan daily challenge yang selesai).':'Total XP sejak awal.'?></p><?php app_foot();
