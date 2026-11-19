<?php require __DIR__.'/includes/bootstrap.php';
$raw=(string)($_GET['token']??$_POST['token']??'');$t=find_token('password_resets',$raw);$err='';
if(!$t){flash('danger','Link reset tidak valid, sudah dipakai, atau kedaluwarsa. Minta link baru.');redirect('forgot.php');}
if($_SERVER['REQUEST_METHOD']==='POST'){
 csrf_check();$pw=(string)($_POST['password']??'');
 if(!strong_password($pw))$err='Password minimal 8 karakter dan mengandung huruf serta angka.';
 elseif($pw!==(string)($_POST['password2']??''))$err='Konfirmasi password tidak sama.';
 else{
  db()->prepare('UPDATE users SET password_hash=? WHERE id=?')->execute([password_hash($pw,PASSWORD_DEFAULT),$t['user_id']]);
  db()->prepare('UPDATE password_resets SET used_at=NOW() WHERE user_id=? AND used_at IS NULL')->execute([$t['user_id']]);
  flash('success','Password diperbarui. Silakan login dengan password baru.');redirect('login.php');
 }
}
$title='Reset Password'; require __DIR__.'/includes/auth_top.php'; ?>
<h1 class="h3 fw-bold">Buat password baru</h1>
<?php if($err):?><div class="alert alert-danger"><?=e($err)?></div><?php endif;?>
<form method="post" class="needs-validation" novalidate><?=csrf_field()?><input type="hidden" name="token" value="<?=e($raw)?>">
<div class="mb-3"><label class="form-label" for="password">Password baru</label><div class="input-group"><input type="password" class="form-control" id="password" name="password" required minlength="8" data-strength="#meter" pattern="(?=.*[A-Za-z])(?=.*\d).{8,}"><button class="btn btn-outline-secondary" type="button" data-toggle-pw="#password" aria-label="Tampilkan password"><i class="bi bi-eye"></i></button><div class="invalid-feedback">Minimal 8 karakter, ada huruf dan angka.</div></div><div class="meter mt-2"><i id="meter"></i></div></div>
<div class="mb-3"><label class="form-label" for="password2">Konfirmasi password</label><input type="password" class="form-control" id="password2" name="password2" required data-match="#password"><div class="invalid-feedback">Konfirmasi harus sama.</div></div>
<button class="btn btn-k w-100">Simpan password</button></form>
<?php require __DIR__.'/includes/auth_bottom.php';
