<?php
const TYPE_LABEL=['learn'=>'Learn','understand'=>'Kode','practice'=>'Latihan','minigame'=>'Mini Game','quiz'=>'Final Quiz'];
const TYPE_ICON=['learn'=>'bi-book','understand'=>'bi-code-slash','practice'=>'bi-terminal','minigame'=>'bi-controller','quiz'=>'bi-trophy-fill'];
// Susun jalur: kategori > level > step. Step terbuka hanya jika step sebelumnya (berurutan di seluruh jalur) sudah selesai.
function path_tree(int $uid,string $slug):array{
 $s=db()->prepare("SELECT m.id mid,m.name mname,lv.id lid,lv.level_no,lv.title ltitle,lv.summary,le.id id,le.step_no,le.type,le.xp_reward,(p.status='completed') done
  FROM learning_paths lp JOIN modules m ON m.path_id=lp.id JOIN levels lv ON lv.module_id=m.id JOIN lessons le ON le.level_id=lv.id
  LEFT JOIN lesson_progress p ON p.lesson_id=le.id AND p.user_id=? WHERE lp.slug=? ORDER BY m.sort_order,lv.level_no,le.step_no");
 $s->execute([$uid,$slug]);$tree=[];$prev=true;
 foreach($s as $r){
  $st=$r['done']?'done':($prev?'cur':'lock');$prev=(bool)$r['done'];
  $tree[$r['mid']]['id']=(int)$r['mid'];$tree[$r['mid']]['name']=$r['mname'];
  $tree[$r['mid']]['levels'][$r['lid']]['no']=(int)$r['level_no'];$tree[$r['mid']]['levels'][$r['lid']]['title']=$r['ltitle'];$tree[$r['mid']]['levels'][$r['lid']]['summary']=$r['summary'];
  $tree[$r['mid']]['levels'][$r['lid']]['steps'][]=['id'=>(int)$r['id'],'type'=>$r['type'],'xp'=>(int)$r['xp_reward'],'state'=>$st];
 }
 return $tree;
}
function tree_counts(array $items):array{ // hitung [selesai,total] untuk satu kategori
 $d=0;$t=0;foreach($items['levels'] as $l)foreach($l['steps'] as $s){$t++;if($s['state']==='done')$d++;}return [$d,$t];
}
function find_lesson(int $uid,int $lessonId):?array{
 $q=db()->prepare('SELECT lp.slug,le.id,le.type,le.title,le.content,le.xp_reward,lv.title ltitle FROM lessons le JOIN levels lv ON lv.id=le.level_id JOIN modules m ON m.id=lv.module_id JOIN learning_paths lp ON lp.id=m.path_id WHERE le.id=?');
 $q->execute([$lessonId]);$row=$q->fetch();if(!$row)return null;
 foreach(path_tree($uid,$row['slug']) as $m)foreach($m['levels'] as $l)foreach($l['steps'] as $s)if($s['id']===$lessonId){$row['state']=$s['state'];$row['mid']=$m['id'];return $row;}
 return null;
}
