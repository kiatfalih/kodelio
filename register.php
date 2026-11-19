<?php require __DIR__.'/includes/bootstrap.php'; require __DIR__.'/includes/mailer.php';
if(current_user())redirect('dashboard.php');
$err=[];$old=['name'=>'','email'=>''];
if($_SERVER['REQUEST_METHOD']==='POST'){
 csrf_check();
 $name=trim((string)($_POST['name']??''));$email=strtolower(trim((string)($_POST['email']??'')));$pw=(string)($_POST['password']??'');$pw2=(string)($_POST['password2']??'');
 $old=['name'=>$name,'email'=>$email];
 if(mb_strlen($name)<2||mb_strlen($name)>100)$err['name']='Nama lengkap 2–100 karakter.';
 if(!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($email)>190)$err['email']='Format email tidak valid.';
 if(!strong_password($pw))$err['password']='Password minimal 8 karakter dan mengandung huruf serta angka.';
 if($pw!==$pw2)$err['password2']='Konfirmasi password tidak sama.';
 if(empty($_POST['terms']))$err['terms']='Kamu harus menyetujui syarat dan ketentuan.';
 if(!$err){
  $s=db()->prepare('SELECT id FROM users WHERE email=?');$s->execute([$email]);
  if($s->fetch())$err['email']='Email sudah digunakan akun lain.';
 }
 if(!$err){
  db()->prepare('INSERT INTO users(name,email,password_hash) VALUES(?,?,?)')->execute([$name,$email,password_hash($pw,PASSWORD_DEFAULT)]);
  $uid=(int)db()->lastInsertId();
  db()->prepare('INSERT INTO user_points(user_id) VALUES(?)')->execute([$uid]);
  db()->prepare('INSERT INTO user_streaks(user_id) VALUES(?)')->execute([$uid]);
  $sent=mail_verify($email,$name,make_token('email_verifications',$uid,1440));
  flash($sent?'success':'warning',$sent?'Akun dibuat! Cek email kamu untuk verifikasi sebelum login.':'Akun dibuat, tetapi email verifikasi gagal dikirim. Coba login lalu pilih "Kirim ulang verifikasi".');
  redirect('login.php');
 }
}
$title='Daftar'; require __DIR__.'/includes/auth_top.php'; ?>
<h1 class="h3 fw-bold">Buat akun baru</h1><p class="text-muted">Mulai belajar dari nol, gratis.</p>
<form method="post" class="needs-validation" novalidate><?=csrf_field()?>
<div class="mb-3"><label class="form-label" for="name">Nama lengkap</label><input class="form-control <?=isset($err['name'])?'is-invalid':''?>" id="name" name="name" value="<?=e($old['name'])?>" required minlength="2" maxlength="100"><div class="invalid-feedback"><?=e($err['name']??'Isi nama lengkap.')?></div></div>
<div class="mb-3"><label class="form-label" for="email">Email</label><input type="email" class="form-control <?=isset($err['email'])?'is-invalid':''?>" id="email" name="email" value="<?=e($old['email'])?>" required><div class="invalid-feedback"><?=e($err['email']??'Isi email yang valid.')?></div></div>
<div class="mb-3"><label class="form-label" for="password">Password</label><div class="input-group"><input type="password" class="form-control <?=isset($err['password'])?'is-invalid':''?>" id="password" name="password" required minlength="8" data-strength="#meter" pattern="(?=.*[A-Za-z])(?=.*\d).{8,}"><button class="btn btn-outline-secondary" type="button" data-toggle-pw="#password" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button><div class="invalid-feedback"><?=e($err['password']??'Minimal 8 karakter, ada huruf dan angka.')?></div></div><div class="meter mt-2"><i id="meter"></i></div></div>
<div class="mb-3"><label class="form-label" for="password2">Konfirmasi password</label><input type="password" class="form-control <?=isset($err['password2'])?'is-invalid':''?>" id="password2" name="password2" required data-match="#password"><div class="invalid-feedback"><?=e($err['password2']??'Konfirmasi harus sama dengan password.')?></div></div>
<div class="form-check mb-3"><input class="form-check-input <?=isset($err['terms'])?'is-invalid':''?>" type="checkbox" id="terms" name="terms" required><label class="form-check-label" for="terms">Saya menyetujui syarat dan ketentuan Kodelio.</label><div class="invalid-feedback"><?=e($err['terms']??'Centang persetujuan.')?></div></div>
<button class="btn btn-k w-100" type="submit">Register</button></form>
<p class="text-center mt-3 mb-0">Sudah punya akun? <a href="login.php">Login</a></p>
<?php require __DIR__.'/includes/auth_bottom.php';
