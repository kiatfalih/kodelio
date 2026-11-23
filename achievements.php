<?php require __DIR__.'/includes/bootstrap.php'; require __DIR__.'/includes/app_top.php';
$u=require_login();$uid=(int)$u['id'];
$s=db()->prepare('SELECT a.*,ua.earned_at FROM achievements a LEFT JOIN user_achievements ua ON ua.achievement_id=a.id AND ua.user_id=? ORDER BY ua.earned_at IS NULL,ua.earned_at DESC,a.id');$s->execute([$uid]);$A=$s->fetchAll();
$n=count(array_filter($A,fn($a)=>$a['earned_at']));app_head('Achievement','ach');?>
<h1 class="fw-bold mb-1"><i class="bi bi-trophy text-warning"></i> Achievement</h1><p class="text-muted mb-4"><?=$n?> dari <?=count($A)?> badge sudah kamu dapatkan.</p>
<div class="row g-3"><?php foreach($A as $a):$on=(bool)$a['earned_at'];?><div class="col-6 col-md-4"><div class="card-k p-3 h-100 ach-card <?=$on?'':'off'?>"><div class="ic"><i class="bi <?=e($a['icon']?:'bi-trophy')?>"></i></div><b><?=e($a['name'])?></b>
<p class="small text-muted mb-1"><?=e($a['description'])?></p><small><?=$on?'<span class="text-success"><i class="bi bi-check-circle-fill"></i> '.date('d M Y',strtotime($a['earned_at'])).'</span>':'Belum didapat'?><?=$a['xp_bonus']?' · +'.(int)$a['xp_bonus'].' XP':''?></small></div></div><?php endforeach;?></div>
<?php app_foot();
