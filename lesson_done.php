<?php require __DIR__.'/includes/bootstrap.php'; require __DIR__.'/includes/learning.php'; require __DIR__.'/includes/progress.php';
header('Content-Type: application/json');
function out(bool $ok,string $msg,array $x=[]):void{echo json_encode(array_merge(['ok'=>$ok,'msg'=>$msg],$x));exit;}
$u=require_login();$uid=(int)$u['id'];csrf_check();
$id=(int)($_POST['id']??0);$L=find_lesson($uid,$id);
if(!$L)out(false,'Step tidak ditemukan.');
if($L['state']==='lock')out(false,'Step ini masih terkunci.');
if(!in_array($L['type'],['learn','understand','practice'],true))out(false,'Step ini diselesaikan lewat mini game atau kuis (tahap 8-9).');
if($L['type']==='practice'){
 $r=db()->prepare('SELECT check_rules FROM lessons WHERE id=?');$r->execute([$id]);$code=(string)($_POST['code']??'');
 foreach(json_decode((string)$r->fetchColumn()?:'[]',true)?:[] as $rule)if(@preg_match('~'.$rule['re'].'~is',$code)===0)out(false,$rule['msg']);
}
$xp=complete_lesson(db(),$uid,$L);
$flat=flat_steps($uid,$L['slug']);$k=array_search($id,$flat,true);$next=($k!==false&&isset($flat[$k+1]))?$flat[$k+1]:null;
out(true,$xp?'Step selesai!':'Step ini sudah selesai.',['xp'=>$xp,'next'=>$next]);
