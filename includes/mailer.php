<?php
use PHPMailer\PHPMailer\PHPMailer;
function send_mail(string $to,string $name,string $subject,string $html):bool{
 $GLOBALS['mail_error']='';
 $auto=__DIR__.'/../vendor/autoload.php';$man=__DIR__.'/PHPMailer/src/';
 if(is_file($auto)){require_once $auto;}
 elseif(is_file($man.'PHPMailer.php')){require_once $man.'Exception.php';require_once $man.'PHPMailer.php';require_once $man.'SMTP.php';}
 else{$GLOBALS['mail_error']='PHPMailer belum terpasang (jalankan composer install, atau salin ke includes/PHPMailer/src/).';error_log($GLOBALS['mail_error']);return false;}
 $s=cfg('smtp');
 if(empty($s['user'])||empty($s['pass'])||strpos((string)$s['host'],'example.com')!==false){$GLOBALS['mail_error']='SMTP di config/config.php belum diisi (host, user, pass).';error_log($GLOBALS['mail_error']);return false;}
 $m=new PHPMailer(true);
 try{
  $m->isSMTP();$m->Host=$s['host'];$m->SMTPAuth=true;$m->Username=$s['user'];$m->Password=$s['pass'];$m->SMTPSecure=$s['secure']??'tls';$m->Port=$s['port'];$m->CharSet='UTF-8';
  $m->setFrom($s['from'],$s['from_name']);$m->addAddress($to,$name);$m->isHTML(true);$m->Subject=$subject;$m->Body=$html;$m->AltBody=strip_tags($html);$m->send();return true;
 }catch(Throwable $x){$GLOBALS['mail_error']=$x->getMessage();error_log('Mail error: '.$x->getMessage());return false;}
}
function mail_verify(string $to,string $name,string $raw):bool{
 $url=cfg('app_url').'/verify.php?token='.$raw;
 return send_mail($to,$name,'Verifikasi email Kodelio',"<p>Halo ".e($name).",</p><p>Klik tombol berikut untuk mengaktifkan akun (berlaku 24 jam):</p><p><a href=\"$url\" style=\"background:#5b4bff;color:#fff;padding:12px 20px;border-radius:8px;text-decoration:none\">Verifikasi Email</a></p><p>Atau buka: $url</p>");
}
function mail_reset(string $to,string $name,string $raw):bool{
 $url=cfg('app_url').'/reset.php?token='.$raw;
 return send_mail($to,$name,'Reset password Kodelio',"<p>Halo ".e($name).",</p><p>Kami menerima permintaan reset password. Link berlaku 30 menit dan hanya bisa dipakai sekali:</p><p><a href=\"$url\" style=\"background:#5b4bff;color:#fff;padding:12px 20px;border-radius:8px;text-decoration:none\">Atur Password Baru</a></p><p>Jika bukan kamu yang meminta, abaikan email ini.</p>");
}
