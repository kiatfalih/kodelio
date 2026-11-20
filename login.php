<?php require __DIR__.'/includes/bootstrap.php'; require __DIR__.'/includes/mailer.php';
if(current_user())redirect('dashboard.php');
$error='';$unverified='';$email='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 csrf_check();
 $email=strtolower(trim((string)($_POST['email']??'')));
 $s=db()->prepare('SELECT * FROM users WHERE email=?');$s->execute([$email]);$u=$s->fetch();
 if(($_POST['action']??'')==='resend'){
  $sent=true;if($u&&!$u['email_verified_at'])$sent=mail_verify($u['email'],$u['name'],make_token('email_verifications',(int)$u['id'],1440));
  if(!$sent)flash(!empty($GLOBALS['cfg']['demo_mode'])?'danger':'warning',!empty($GLOBALS['cfg']['demo_mode'])?'Email gagal dikirim: '.($GLOBALS['mail_error']??'tidak diketahui'):'Email belum bisa dikirim saat ini. Coba lagi nanti.');
  else flash('info','Jika akun itu belum terverifikasi, email verifikasi baru sudah dikirim.');
  redirect('login.php');
 }
 $pw=(string)($_POST['password']??'');
 $ok=password_verify($pw,$u['password_hash']??'$2y$10$usesomesillystringforsaltuQ5wYk4F9vXk3kqQe7y3S0M2bq5mG'); // waktu verifikasi dibuat setara
 if(!$u||!$ok)$error='Email atau password salah.';
 elseif(!$u['email_verified_at']&&($GLOBALS['cfg']['require_verification']??true))$unverified=$email;
 else{
  session_regenerate_id(true);$_SESSION['uid']=(int)$u['id'];
  if(!empty($_POST['remember']))setcookie(session_name(),session_id(),['expires'=>time()+60*60*24*30,'path'=>'/','httponly'=>true,'samesite'=>'Lax','secure'=>!empty($_SERVER['HTTPS'])]);
  db()->prepare('UPDATE users SET last_login_at=NOW() WHERE id=?')->execute([$u['id']]);
  redirect(empty($u['active_path_id'])?'choose-path.php':'dashboard.php');
 }
}
$title='Login'; require __DIR__.'/includes/auth_top.php'; ?>
<h1 class="h3 fw-bold">Selamat datang kembali</h1><p class="text-muted">Lanjutkan perjalanan belajarmu.</p>
<?php if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?>
<?php if($unverified):?><div class="alert alert-warning">Email belum diverifikasi. Cek inbox kamu, atau kirim ulang:
<form method="post" class="mt-2"><?=csrf_field()?><input type="hidden" name="action" value="resend"><input type="hidden" name="email" value="<?=e($unverified)?>"><button class="btn btn-sm btn-outline-dark">Kirim ulang verifikasi</button></form></div><?php endif;?>
<form method="post" class="needs-validation" novalidate><?=csrf_field()?>
<div class="mb-3"><label class="form-label" for="email">Email</label><input type="email" class="form-control" id="email" name="email" value="<?=e($email)?>" required><div class="invalid-feedback">Isi email yang valid.</div></div>
<div class="mb-3"><label class="form-label" for="password">Password</label><div class="input-group"><input type="password" class="form-control" id="password" name="password" required><button class="btn btn-outline-secondary" type="button" data-toggle-pw="#password" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button><div class="invalid-feedback">Isi password.</div></div></div>
<div class="d-flex justify-content-between mb-3"><div class="form-check"><input class="form-check-input" type="checkbox" id="remember" name="remember"><label class="form-check-label" for="remember">Remember me</label></div><a href="forgot.php">Forgot password?</a></div>
<button class="btn btn-k w-100" type="submit">Login</button></form>
<p class="text-center mt-3 mb-0">Belum punya akun? <a href="register.php">Register</a></p>
<?php require __DIR__.'/includes/auth_bottom.php';
