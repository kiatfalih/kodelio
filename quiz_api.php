<?php require __DIR__.'/includes/bootstrap.php'; require __DIR__.'/includes/learning.php'; require __DIR__.'/includes/progress.php';
header('Content-Type: application/json');
function out(array $d,int $code=200):void{http_response_code($code);echo json_encode($d);exit;}
$u=require_login();$uid=(int)$u['id'];csrf_check();$pdo=db();$id=(int)($_POST['id']??0);$L=find_lesson($uid,$id);
if(!$L||$L['state']==='lock'||!in_array($L['type'],['minigame','quiz'],true))out(['error'=>'Tidak diizinkan.'],403);
$s=$pdo->prepare('SELECT * FROM questions WHERE lesson_id=? ORDER BY id');$s->execute([$id]);$Q=[];
foreach($s->fetchAll() as $q){$Q[$q['id']]=$q+['answers'=>[]];}
if($Q){$in=implode(',',array_map('intval',array_keys($Q)));foreach($pdo->query("SELECT * FROM answers WHERE question_id IN ($in) ORDER BY id") as $a)$Q[$a['question_id']]['answers'][$a['id']]=$a;}
$ps=$pdo->prepare('SELECT lv.pass_score FROM lessons le JOIN levels lv ON lv.id=le.level_id WHERE le.id=?');$ps->execute([$id]);$passScore=(int)($ps->fetchColumn()?:70);
function res(array $q,bool $ok,string $wrong,string $ct):array{
 return ['id'=>(int)$q['id'],'correct'=>$ok,'feedback'=>$ok?($q['feedback_correct']?:'Mantap, jawabanmu benar!'):$wrong,'explanation'=>$q['explanation'],'hint'=>$q['hint'],'correct_text'=>$ct];
}
function grade(array $q,$raw):array{
 $k=$q['kind']??'choice';
 if($k==='choice'){
  $aid=(int)$raw;$ok=isset($q['answers'][$aid])&&$q['answers'][$aid]['is_correct'];$ct='';foreach($q['answers'] as $a)if($a['is_correct'])$ct=$a['answer_text'];
  return res($q,$ok,(string)(($q['answers'][$aid]['feedback']??'')?:($q['feedback_wrong']?:'Jawaban ini kurang tepat.')),$ct);
 }
 $p=json_decode((string)$q['payload'],true);$a=json_decode((string)$raw,true);
 if($k==='arrange'){$bad=[];foreach($p['order'] as $i=>$x)if(($a[$i]??null)!==$x)$bad[]=$i+1;
  return res($q,!$bad,'Urutan belum tepat. Posisi yang masih salah: baris ke-'.implode(', ',$bad).'.',implode("\n",$p['order']));}
 if($k==='match'){$n=0;foreach($p['pairs'] as $i=>$pr)if(($a[$i]??'')===$pr[1])$n++;$m=count($p['pairs']);
  return res($q,$n===$m,"$n dari $m pasangan sudah benar. Periksa lagi sisanya.",implode("\n",array_map(fn($x)=>$x[0].' = '.$x[1],$p['pairs'])));}
 if($k==='findbug')return res($q,(string)$a===(string)$p['bug'],'Baris yang kamu pilih sudah benar sintaksnya. Cari baris lain.','Baris '.($p['bug']+1).': '.$p['fix']);
 return res($q,false,'Tipe soal tidak dikenal.','');
}
function pub(array $q):array{
 $k=$q['kind']??'choice';$b=['id'=>(int)$q['id'],'text'=>$q['prompt'],'code'=>$q['code_example'],'kind'=>$k];$p=json_decode((string)$q['payload'],true);
 if($k==='arrange'){$x=$p['order'];if(count($x)>1)do{shuffle($x);}while($x===$p['order']);$b['pieces']=$x;}
 elseif($k==='match'){$r=array_column($p['pairs'],1);shuffle($r);$b['lefts']=array_column($p['pairs'],0);$b['rights']=$r;}
 elseif($k==='findbug')$b['lines']=$p['lines'];
 else $b['answers']=array_values(array_map(fn($a)=>['id'=>(int)$a['id'],'text'=>$a['answer_text']],$q['answers']));
 return $b;
}
switch($_POST['action']??''){
case 'get':unset($_SESSION['g'][$id]);
 $by=[];foreach($Q as $q)$by[$q['kind']??'choice'][]=$q;$mix=[];$n=count($Q);// selang-seling tipe soal agar bervariasi
 while(count($mix)<$n){foreach(array_keys($by) as $k){if($by[$k])$mix[]=array_shift($by[$k]);}}
 out(['pass'=>$passScore,'questions'=>array_map('pub',$mix)]);
case 'check':
 $qid=(int)($_POST['question']??0);if(!isset($Q[$qid]))out(['error'=>'Soal tidak ada.'],404);$r=grade($Q[$qid],$_POST['answer']??'');
 $g=$_SESSION['g'][$id][$qid]??['n'=>0,'ok'=>false];$_SESSION['g'][$id][$qid]=['n'=>$g['n']+1,'ok'=>$g['ok']||$r['correct']];out($r);
case 'finish_game':
 $first=0;$det=[];foreach($Q as $qid=>$q){$g=$_SESSION['g'][$id][$qid]??null;if(!$g||!$g['ok'])out(['error'=>'Masih ada soal yang belum benar.'],422);if($g['n']===1)$first++;$det[]=['q'=>$qid,'tries'=>$g['n']];}
 $xp=complete_lesson($pdo,$uid,$L);$pct=$Q?(int)round($first/count($Q)*100):0;
 $map=['choice'=>'quick_quiz','arrange'=>'code_arrangement','match'=>'match_the_code','findbug'=>'find_the_bug'];$first_q=reset($Q);
 $pdo->prepare('INSERT INTO game_results(user_id,lesson_id,game_type,score,xp_earned,details) VALUES(?,?,?,?,?,?)')->execute([$uid,$id,$map[$first_q['kind']??'choice']??'quick_quiz',$pct,$xp,json_encode($det)]);
out(['score'=>$pct,'first'=>$first,'total'=>count($Q),'xp'=>$xp]);
case 'submit':
 $ans=json_decode((string)($_POST['answers']??'{}'),true)?:[];$rs=[];$ok=0;$wrong=[];
 foreach($Q as $qid=>$q){$r=grade($q,$ans[$qid]??'');$rs[]=$r;if($r['correct'])$ok++;else $wrong[]=['question_id'=>$qid,'chosen'=>(int)($ans[$qid]??0)];}
 $score=$Q?(int)round($ok/count($Q)*100):0;$pass=$score>=$passScore;
 $pdo->prepare('INSERT INTO quiz_results(user_id,lesson_id,score,passed,wrong_details) VALUES(?,?,?,?,?)')->execute([$uid,$id,$score,(int)$pass,json_encode($wrong)]);
 $xp=0;if($pass)$xp=complete_lesson($pdo,$uid,$L);if($score===100)give_achievement($pdo,$uid,'perfect_quiz');
 out(['score'=>$score,'passed'=>$pass,'pass'=>$passScore,'xp'=>$xp,'results'=>$rs]);
default:out(['error'=>'Aksi tidak dikenal.'],400);
}
