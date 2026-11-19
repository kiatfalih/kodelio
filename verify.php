<?php require __DIR__.'/includes/bootstrap.php';
$t=find_token('email_verifications',(string)($_GET['token']??''));
if($t){db()->prepare('UPDATE users SET email_verified_at=NOW() WHERE id=? AND email_verified_at IS NULL')->execute([$t['user_id']]);db()->prepare('UPDATE email_verifications SET used_at=NOW() WHERE id=?')->execute([$t['id']]);flash('success','Email terverifikasi! Silakan login.');}
else flash('danger','Link verifikasi tidak valid atau sudah kedaluwarsa. Login lalu kirim ulang verifikasi.');
redirect('login.php');
