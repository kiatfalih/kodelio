<?php
// Logika penyelesaian step: XP, streak, achievement. Dipakai lesson.php dan lesson_done.php.
function add_xp(PDO $pdo,int $uid,int $xp):void{
 $pdo->prepare('INSERT INTO user_points(user_id,xp,level) VALUES(?,?,FLOOR(?/100)+1) ON DUPLICATE KEY UPDATE xp=xp+VALUES(xp),level=FLOOR(xp/100)+1')->execute([$uid,$xp,$xp]);
}
function give_achievement(PDO $pdo,int $uid,string $code):void{
 $a=$pdo->prepare('SELECT id,xp_bonus FROM achievements WHERE code=?');$a->execute([$code]);$r=$a->fetch();if(!$r)return;
 $i=$pdo->prepare('INSERT IGNORE INTO user_achievements(user_id,achievement_id) VALUES(?,?)');$i->execute([$uid,$r['id']]);
 if($i->rowCount()&&(int)$r['xp_bonus']>0)add_xp($pdo,$uid,(int)$r['xp_bonus']);
}
function bump_streak(PDO $pdo,int $uid):void{
 $s=$pdo->prepare('SELECT current_streak,longest_streak,last_activity_date FROM user_streaks WHERE user_id=?');$s->execute([$uid]);$r=$s->fetch();
 $today=date('Y-m-d');
 if(!$r){$pdo->prepare('INSERT INTO user_streaks(user_id,current_streak,longest_streak,last_activity_date) VALUES(?,1,1,?)')->execute([$uid,$today]);return;}
 if($r['last_activity_date']===$today)return;
 $cur=$r['last_activity_date']===date('Y-m-d',strtotime('-1 day'))?(int)$r['current_streak']+1:1;
 $pdo->prepare('UPDATE user_streaks SET current_streak=?,longest_streak=?,last_activity_date=? WHERE user_id=?')->execute([$cur,max($cur,(int)$r['longest_streak']),$today,$uid]);
 if($cur>=7)give_achievement($pdo,$uid,'consistent_learner');
}
// Tandai step selesai. Mengembalikan XP yang didapat (0 jika sudah selesai sebelumnya).
function complete_lesson(PDO $pdo,int $uid,array $L):int{
 $s=$pdo->prepare('SELECT status FROM lesson_progress WHERE user_id=? AND lesson_id=?');$s->execute([$uid,$L['id']]);
 if($s->fetchColumn()==='completed')return 0;
 $xp=(int)$L['xp_reward'];$pdo->beginTransaction();
 try{
  $pdo->prepare("INSERT INTO lesson_progress(user_id,lesson_id,status,completed_at) VALUES(?,?, 'completed',NOW()) ON DUPLICATE KEY UPDATE status='completed',completed_at=NOW()")->execute([$uid,$L['id']]);
  add_xp($pdo,$uid,$xp);bump_streak($pdo,$uid);give_achievement($pdo,$uid,'first_step');$pdo->commit();
 }catch(Throwable $e){$pdo->rollBack();throw $e;}
 return $xp;
}
function flat_steps(int $uid,string $slug):array{ // id step berurutan di seluruh jalur
 $ids=[];foreach(path_tree($uid,$slug) as $m)foreach($m['levels'] as $l)foreach($l['steps'] as $s)$ids[]=$s['id'];return $ids;
}
