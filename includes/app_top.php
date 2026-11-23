<?php
// Kerangka halaman untuk halaman setelah login (navbar + menu).
function app_head(string $title,string $active=''):void{
 $nav=[['dash','dashboard.php','Dashboard','bi-house'],['daily','daily.php','Daily','bi-fire'],['ach','achievements.php','Achievement','bi-trophy'],['lb','leaderboard.php','Leaderboard','bi-bar-chart'],['hist','history.php','Riwayat','bi-clock-history']];
 ?><!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e($title)?> · Kodelio</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"><link href="assets/css/style.css" rel="stylesheet"><link href="assets/css/onboarding.css" rel="stylesheet"><link href="assets/css/app.css" rel="stylesheet"></head><body>
<nav class="navbar k-nav"><div class="container"><a class="brand" href="dashboard.php"><i class="bi bi-braces"></i> Kodelio</a>
<div class="app-links order-3 order-md-2"><?php foreach($nav as $n):?><a href="<?=$n[1]?>" class="<?=$active===$n[0]?'on':''?>"><i class="bi <?=$n[3]?>"></i> <?=$n[2]?></a><?php endforeach;?></div>
<form method="post" action="logout.php" class="order-2 order-md-3"><?=csrf_field()?><button class="btn btn-outline-k btn-sm"><i class="bi bi-box-arrow-right"></i></button></form></div></nav>
<main class="container py-4" style="max-width:980px"><?php $f=flash();if($f):?><div class="alert alert-<?=e($f[0])?>"><?=e($f[1])?></div><?php endif;
}
function app_foot():void{echo '</main></body></html>';}
