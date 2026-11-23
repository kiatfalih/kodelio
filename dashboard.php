<?php require __DIR__.'/includes/bootstrap.php'; require __DIR__.'/includes/learning.php'; require __DIR__.'/includes/app_top.php';
$u=require_login();$uid=(int)$u['id'];$pdo=db();
$ap=$pdo->prepare('SELECT lp.name,lp.slug FROM users u JOIN learning_paths lp ON lp.id=u.active_path_id WHERE u.id=?');$ap->execute([$uid]);$act=$ap->fetch();if(!$act)redirect('choose-path.php');
$D=0;$T=0;$LD=0;$cur=null;
foreach(path_tree($uid,$act['slug']) as $m){[$x,$y]=tree_counts($m);$D+=$x;$T+=$y;
 foreach($m['levels'] as $l){$all=!empty($l['steps']);foreach($l['steps'] as $s){if($s['state']!=='done')$all=false;if(!$cur&&$s['state']==='cur')$cur=$s['id'];}if($all)$LD++;}}
$pct=$T?round($D/$T*100):0;
$pt=$pdo->prepare('SELECT xp,level FROM user_points WHERE user_id=?');$pt->execute([$uid]);$P=$pt->fetch()?:['xp'=>0,'level'=>1];
$st=$pdo->prepare('SELECT current_streak,longest_streak,last_activity_date FROM user_streaks WHERE user_id=?');$st->execute([$uid]);$S=$st->fetch()?:['current_streak'=>0,'longest_streak'=>0,'last_activity_date'=>null];
$streak=($S['last_activity_date']&&$S['last_activity_date']>=date('Y-m-d',strtotime('-1 day')))?(int)$S['current_streak']:0;
$ac=$pdo->prepare('SELECT a.name,a.icon FROM user_achievements ua JOIN achievements a ON a.id=ua.achievement_id WHERE ua.user_id=? ORDER BY ua.earned_at DESC LIMIT 3');$ac->execute([$uid]);$ACH=$ac->fetchAll();
$na=$pdo->prepare('SELECT COUNT(*) FROM user_achievements WHERE user_id=?');$na->execute([$uid]);$nAch=(int)$na->fetchColumn();
$av=$pdo->prepare('SELECT ROUND(AVG(score)) FROM quiz_results WHERE user_id=?');$av->execute([$uid]);$avg=$av->fetchColumn();
$dc=$pdo->prepare('SELECT d.xp_reward,(SELECT 1 FROM challenge_progress cp WHERE cp.user_id=? AND cp.challenge_id=d.id) done FROM daily_challenges d WHERE d.challenge_date=CURDATE()');$dc->execute([$uid]);$DC=$dc->fetch();
$rk=$pdo->prepare("SELECT COUNT(*)+1 FROM user_points up JOIN users x ON x.id=up.user_id WHERE x.role='user' AND up.xp>?");$rk->execute([$P['xp']]);$rank=(int)$rk->fetchColumn();
$into=(int)$P['xp']%100;
app_head('Dashboard','dash');?>
<h1 class="fw-bold">Halo, <?=e($u['name'])?>! 👋</h1><p class="text-muted mb-3">Lanjutkan perjalanan belajarmu.</p>
<div class="dash-bar"><span class="pill"><i class="bi bi-bookmark-star-fill"></i> Jalur aktif: <b><?=e($act['name'])?></b></span><a class="btn btn-outline-k" href="choose-path.php"><i class="bi bi-arrow-left-right"></i> Ganti jalur</a><a class="btn btn-outline-k" href="placement.php"><i class="bi bi-compass"></i> Tes kemampuan awal</a></div>
<div class="card-k p-4 mb-3"><div class="d-flex justify-content-between mb-2"><b>Progres belajar</b><b><?=$pct?>%</b></div><div class="progress mb-3" style="height:12px"><div class="progress-bar bg-success" style="width:<?=$pct?>%"></div></div>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2"><small class="text-muted"><?=$D?> dari <?=$T?> step selesai</small>
<?php if($cur):?><a class="btn btn-k" href="lesson.php?id=<?=$cur?>"><i class="bi bi-play-fill"></i> Lanjut belajar</a><?php else:?><a class="btn btn-outline-k" href="path.php?p=<?=e($act['slug'])?>">Buka peta belajar</a><?php endif;?></div></div>
<div class="row g-3 mb-3">
<div class="col-6 col-lg-3"><div class="st"><div class="st-ic" style="background:#fff4dc;color:#a86a00"><i class="bi bi-lightning-charge-fill"></i></div><div class="v"><?=(int)$P['xp']?></div><small>XP · Level <?=(int)$P['level']?></small><div class="progress mt-2" style="height:6px"><div class="progress-bar bg-warning" style="width:<?=$into?>%"></div></div></div></div>
<div class="col-6 col-lg-3"><div class="st"><div class="st-ic" style="background:#ffe9e4;color:#d6451f"><i class="bi bi-fire"></i></div><div class="v"><?=$streak?> hari</div><small>Streak · terbaik <?=(int)$S['longest_streak']?></small></div></div>
<div class="col-6 col-lg-3"><div class="st"><div class="st-ic"><i class="bi bi-layers"></i></div><div class="v"><?=$LD?></div><small>Level selesai · <?=$D?> step</small></div></div>
<div class="col-6 col-lg-3"><div class="st"><div class="st-ic" style="background:#e3f8ec;color:#1f9d55"><i class="bi bi-patch-check"></i></div><div class="v"><?=$avg!==null&&$avg!==false?(int)$avg.'%':'-'?></div><small>Rata-rata nilai kuis</small></div></div></div>
<div class="row g-3 mb-4">
<div class="col-md-4"><a class="card-k hover p-3 d-block text-decoration-none text-reset h-100" href="daily.php"><b><i class="bi bi-fire text-warning"></i> Daily Challenge</b><p class="small text-muted mb-2">Satu soal per hari, bonus <?=(int)($DC['xp_reward']??20)?> XP.</p><?=($DC&&$DC['done'])?'<span class="badge text-bg-success">Selesai hari ini</span>':'<span class="badge text-bg-warning">Belum dikerjakan</span>'?></a></div>
<div class="col-md-4"><a class="card-k hover p-3 d-block text-decoration-none text-reset h-100" href="achievements.php"><b><i class="bi bi-trophy text-warning"></i> Achievement</b><p class="small text-muted mb-2"><?=$nAch?> badge didapat</p><?php foreach($ACH as $a):?><span class="tag"><i class="bi <?=e($a['icon'])?>"></i> <?=e($a['name'])?></span><?php endforeach;?><?php if(!$ACH):?><small class="text-muted">Selesaikan step pertamamu.</small><?php endif;?></a></div>
<div class="col-md-4"><a class="card-k hover p-3 d-block text-decoration-none text-reset h-100" href="leaderboard.php"><b><i class="bi bi-bar-chart text-primary"></i> Leaderboard</b><p class="small text-muted mb-2">Peringkatmu saat ini</p><span class="fs-3 fw-bold">#<?=$rank?></span></a></div></div>
<div class="row g-3"><?php foreach(['beginner'=>['Beginner','bi-flower1','HTML, CSS, JavaScript'],'intermediate'=>['Intermediate','bi-rocket-takeoff','JS lanjutan, MySQL, Backend, API']] as $slug=>$p):
 $tr=path_tree($uid,$slug);$d=0;$t=0;foreach($tr as $m){[$x,$y]=tree_counts($m);$d+=$x;$t+=$y;}?>
<div class="col-md-6"><a class="card-k hover p-4 d-block text-decoration-none text-reset h-100" href="path.php?p=<?=$slug?>"><div class="d-flex align-items-center gap-3 mb-3"><div class="ico-box"><i class="bi <?=$p[1]?>"></i></div><div><b class="fs-5"><?=$p[0]?></b><div class="small text-muted"><?=$p[2]?></div></div></div>
<div class="progress" style="height:8px"><div class="progress-bar bg-success" style="width:<?=$t?round($d/$t*100):0?>%"></div></div><small class="text-muted"><?=$d?> dari <?=$t?> step selesai</small></a></div><?php endforeach;?></div>
<?php app_foot();
