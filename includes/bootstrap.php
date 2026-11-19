<?php
declare(strict_types=1);
$cfgFile=__DIR__.'/../config/config.php';
if(!is_file($cfgFile)){http_response_code(500);exit('Salin config/config.example.php menjadi config/config.php lalu isi konfigurasinya.');}
$GLOBALS['cfg']=require $cfgFile;
session_set_cookie_params(['httponly'=>true,'samesite'=>'Lax','secure'=>!empty($_SERVER['HTTPS'])]);
session_start();
function cfg(string $k){return $GLOBALS['cfg'][$k];}
function db():PDO{static $p=null;if(!$p){$d=cfg('db');$p=new PDO("mysql:host={$d['host']};dbname={$d['name']};charset={$d['charset']}",$d['user'],$d['pass'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);}return $p;}
function e($s):string{return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
function csrf():string{return $_SESSION['csrf']??=bin2hex(random_bytes(32));}
function csrf_field():string{return '<input type="hidden" name="csrf" value="'.e(csrf()).'">';}
function csrf_check():void{if(!hash_equals($_SESSION['csrf']??'',(string)($_POST['csrf']??''))){http_response_code(419);exit('Sesi formulir tidak valid. Muat ulang halaman lalu coba lagi.');}}
function flash(?string $type=null,?string $msg=null){if($type!==null){$_SESSION['flash']=[$type,$msg];return null;}$f=$_SESSION['flash']??null;unset($_SESSION['flash']);return $f;}
function redirect(string $p):void{header('Location: '.$p);exit;}
function current_user():?array{if(empty($_SESSION['uid']))return null;static $u=null;if($u===null){$s=db()->prepare('SELECT id,name,email,role FROM users WHERE id=?');$s->execute([$_SESSION['uid']]);$u=$s->fetch()?:false;}return $u?:null;}
function require_login():array{$u=current_user();if(!$u)redirect('login.php');return $u;}
function require_role(string $r):array{$u=require_login();if($u['role']!==$r){http_response_code(403);exit('Akses ditolak.');}return $u;}
function strong_password(string $p):bool{return strlen($p)>=8&&preg_match('/[A-Za-z]/',$p)&&preg_match('/\d/',$p);}
// Token acak: yang dikirim ke email adalah token mentah, di database hanya disimpan hash SHA-256-nya.
function make_token(string $table,int $uid,int $ttlMin):string{
 if(!in_array($table,['email_verifications','password_resets'],true))throw new InvalidArgumentException();
 db()->prepare("UPDATE $table SET used_at=NOW() WHERE user_id=? AND used_at IS NULL")->execute([$uid]);
 $raw=bin2hex(random_bytes(32));
 db()->prepare("INSERT INTO $table(user_id,token_hash,expires_at) VALUES(?,?,DATE_ADD(NOW(),INTERVAL ? MINUTE))")->execute([$uid,hash('sha256',$raw),$ttlMin]);
 return $raw;
}
function find_token(string $table,string $raw):?array{
 if(!in_array($table,['email_verifications','password_resets'],true)||!preg_match('/^[a-f0-9]{64}$/',$raw))return null;
 $s=db()->prepare("SELECT * FROM $table WHERE token_hash=? AND used_at IS NULL AND expires_at>NOW()");$s->execute([hash('sha256',$raw)]);return $s->fetch()?:null;
}
