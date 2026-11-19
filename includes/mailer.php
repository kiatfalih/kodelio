<?php
use PHPMailer\PHPMailer\PHPMailer;
function send_mail(string $to,string $name,string $subject,string $html):bool{
 $auto=__DIR__.'/../vendor/autoload.php';
 if(!is_file($auto)){error_log('PHPMailer belum terpasang: jalankan composer install');return false;}
 require_once $auto;
 $s=cfg('smtp');$m=new PHPMailer(true);
 try{
  $m->isSMTP();$m->Host=$s['host'];$m->SMTPAuth=true;$m->Username=$s['user'];$m->Password=$s['pass'];$m->SMTPSecure=$s['secure'];$m->Port=$s['port'];$m->CharSet='UTF-8';
  $m->setFrom($s['from'],$s['from_name']);$m->addAddress($to,$name);$m->isHTML(true);$m->Subject=$subject;$m->Body=$html;$m->AltBody=strip_tags($html);$m->send();return true;
 }catch(Throwable $x){error_log('Mail error: '.$x->getMessage());return false;}
}
function mail_verify(string $to,string $name,string $raw):bool{
 $url=cfg('app_url').'/verify.php?token='.$raw;
 return send_mail($to,$name,'Verifikasi email Kodelio',"<p>Halo ".e($name).",</p><p>Klik tombol berikut untuk mengaktifkan akun (berlaku 24 jam):</p><p><a href=\"$url\" style=\"background:#5b4bff;color:#fff;padding:12px 20px;border-radius:8px;text-decoration:none\">Verifikasi Email</a></p><p>Atau buka: $url</p>");
}
function mail_reset(string $to,string $name,string $raw):bool{
 $url=cfg('app_url').'/reset.php?token='.$raw;
 return send_mail($to,$name,'Reset password Kodelio',"<p>Halo ".e($name).",</p><p>Kami menerima permintaan reset password. Link berlaku 30 menit dan hanya bisa dipakai sekali:</p><p><a href=\"$url\" style=\"background:#5b4bff;color:#fff;padding:12px 20px;border-radius:8px;text-decoration:none\">Atur Password Baru</a></p><p>Jika bukan kamu yang meminta, abaikan email ini.</p>");
}
