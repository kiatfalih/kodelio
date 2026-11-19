<?php require __DIR__.'/includes/bootstrap.php'; require __DIR__.'/includes/mailer.php';
$done=false;
if($_SERVER['REQUEST_METHOD']==='POST'){
 csrf_check();$email=strtolower(trim((string)($_POST['email']??'')));
 if(filter_var($email,FILTER_VALIDATE_EMAIL)){
  $s=db()->prepare('SELECT id,name,email FROM users WHERE email=? AND email_verified_at IS NOT NULL');$s->execute([$email]);
  if($u=$s->fetch())mail_reset($u['email'],$u['name'],make_token('password_resets',(int)$u['id'],30));
 }
 $done=true; // pesan sama apa pun hasilnya, supaya tidak membocorkan email terdaftar
}
$title='Lupa Password'; require __DIR__.'/includes/auth_top.php'; ?>
<h1 class="h3 fw-bold">Lupa password?</h1>
<?php if($done):?><div class="alert alert-success">Jika email itu terdaftar, link reset password sudah dikirim (berlaku 30 menit).</div><a class="btn btn-k" href="login.php">Kembali ke login</a>
<?php else:?><p class="text-muted">Masukkan email akunmu, kami kirim link untuk membuat password baru.</p>
<form method="post" class="needs-validation" novalidate><?=csrf_field()?><div class="mb-3"><label class="form-label" for="email">Email</label><input type="email" class="form-control" id="email" name="email" required><div class="invalid-feedback">Isi email yang valid.</div></div><button class="btn btn-k w-100">Kirim link reset</button></form>
<p class="text-center mt-3 mb-0"><a href="login.php">Kembali ke login</a></p><?php endif;?>
<?php require __DIR__.'/includes/auth_bottom.php';
