(()=>{const Z=window.QZ,box=document.getElementById('quiz'),game=Z.type==='minigame';
const post=async d=>{const f=new FormData();f.append('csrf',Z.csrf);f.append('id',Z.id);for(const k in d)f.append(k,d[k]);return(await fetch('quiz_api.php',{method:'POST',body:f})).json()};
const esc=s=>String(s??'').replace(/[&<>"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));
const nl=s=>esc(s).replace(/\n/g,'<br>');
const qHtml=q=>'<p class="fw-semibold mb-2">'+esc(q.text)+'</p>'+(q.code?'<pre class="code-ex">'+esc(q.code)+'</pre>':'');
const note=(ok,h)=>'<div class="alert alert-'+(ok?'success':'danger')+' mt-2 mb-0">'+h+'</div>';
const unlock=()=>{const n=document.getElementById('next');if(n){n.href=Z.next?'lesson.php?id='+Z.next:'path.php?p='+encodeURIComponent(Z.slug);n.classList.remove('disabled')}};
const wrongHtml=j=>'<b>Belum tepat.</b> '+esc(j.feedback)+'<br>Jawaban benar:<br><code>'+nl(j.correct_text)+'</code><br>'+esc(j.explanation)+(j.hint?'<br><i>Petunjuk: '+esc(j.hint)+'</i>':'');
const rightHtml=j=>'<b>Benar!</b> '+esc(j.feedback)+' '+esc(j.explanation);
const R={
 choice:q=>({html:q.answers.map(a=>'<label class="qz-opt"><input type="radio" name="q'+q.id+'" value="'+a.id+'"> '+esc(a.text)+'</label>').join(''),get:()=>{const s=box.querySelector('input[name=q'+q.id+']:checked');return s&&s.value}}),
 arrange:q=>{let bank=q.pieces.map((t,k)=>({t,k})),ans=[];
  const draw=()=>{const b=document.getElementById('bk'),a=document.getElementById('an');
   b.innerHTML=bank.map(p=>'<button type="button" class="btn btn-outline-k btn-sm m-1 font-monospace" data-k="'+p.k+'">'+esc(p.t)+'</button>').join('');
   a.innerHTML=ans.length?ans.map(p=>'<span role="button" data-k="'+p.k+'">'+esc(p.t)+'</span>').join(''):'<span class="text-secondary">Klik potongan kode di atas</span>';
   b.querySelectorAll('button').forEach(x=>x.onclick=()=>{const p=bank.find(y=>y.k==x.dataset.k);bank=bank.filter(y=>y!==p);ans.push(p);draw()});
   a.querySelectorAll('span[data-k]').forEach(x=>x.onclick=()=>{const p=ans.find(y=>y.k==x.dataset.k);ans=ans.filter(y=>y!==p);bank.push(p);draw()})};
  return{html:'<div id="bk"></div><div class="small text-muted mt-2">Susunanmu (klik baris untuk melepas):</div><pre id="an" class="code-ex"></pre>',setup:draw,get:()=>bank.length?null:JSON.stringify(ans.map(p=>p.t))}},
 match:q=>({html:q.lefts.map(l=>'<div class="d-flex align-items-center gap-2 mb-2"><code class="flex-fill">'+esc(l)+'</code><select class="form-select w-50"><option value="">Pilih...</option>'+q.rights.map(r=>'<option>'+esc(r)+'</option>').join('')+'</select></div>').join(''),get:()=>{const v=[...box.querySelectorAll('select')].map(s=>s.value);return v.includes('')?null:JSON.stringify(v)}}),
 findbug:q=>{let sel=null;return{html:'<div id="bg">'+q.lines.map((l,k)=>'<div class="code-ex mb-1 py-1 px-2" style="cursor:pointer" data-k="'+k+'">'+esc(l)+'</div>').join('')+'</div>',
  setup(){box.querySelectorAll('#bg div').forEach(d=>d.onclick=()=>{sel=d.dataset.k;box.querySelectorAll('#bg div').forEach(x=>x.style.outline=x===d?'3px solid #ffb020':'')})},get:()=>sel}}};
let qs=[],i=0,pass=70;
function show(){const q=qs[i],t=R[q.kind||'choice'](q);
 box.innerHTML='<div class="qz-num mb-1">Soal '+(i+1)+' dari '+qs.length+'</div><div class="meter mb-3"><i style="width:'+(i/qs.length*100)+'%"></i></div>'+qHtml(q)+t.html+'<button class="btn btn-k mt-2" id="ck">Periksa</button><div id="r" class="mt-2"></div>';
 if(t.setup)t.setup();const ck=document.getElementById('ck'),r=document.getElementById('r');
 ck.onclick=async()=>{const v=t.get();if(v==null){r.innerHTML=note(false,'Lengkapi jawabanmu dulu.');return}
  const j=await post({action:'check',question:q.id,answer:v});
  if(j.correct){r.innerHTML=note(true,rightHtml(j));ck.textContent=i<qs.length-1?'Soal berikutnya':'Selesai';ck.onclick=next}
  else{r.innerHTML=note(false,wrongHtml(j)+'<br><button class="btn btn-outline-k btn-sm mt-2" id="rt">Coba lagi</button>');document.getElementById('rt').onclick=show}}}
async function next(){if(++i<qs.length)return show();const j=await post({action:'finish_game'});
 box.innerHTML=note(true,'<b>Mini game selesai!</b> Benar di percobaan pertama: '+j.first+'/'+j.total+(j.xp?' <b>+'+j.xp+' XP</b>':''));unlock()}
function quiz(){box.innerHTML=qs.map((q,k)=>'<div class="mb-4"><div class="qz-num">Soal '+(k+1)+'</div>'+qHtml(q)+R.choice(q).html+'<div id="r'+q.id+'" class="mt-2"></div></div>').join('')+'<button class="btn btn-k" id="sb">Kumpulkan jawaban</button><div id="sum" class="mt-3"></div>';
 document.getElementById('sb').onclick=async function(){const a={};for(const q of qs){const s=box.querySelector('input[name=q'+q.id+']:checked');if(!s){document.getElementById('sum').innerHTML=note(false,'Jawab semua soal dulu.');return}a[q.id]=s.value}
  const j=await post({action:'submit',answers:JSON.stringify(a)});this.disabled=true;
  j.results.forEach(r=>{document.getElementById('r'+r.id).innerHTML=note(r.correct,r.correct?rightHtml(r):wrongHtml(r))});
  document.getElementById('sum').innerHTML=note(j.passed,'<b>Nilai: '+j.score+'%</b> '+(j.passed?'Lulus!'+(j.xp?' <b>+'+j.xp+' XP</b>':''):'Belum lulus, minimal '+j.pass+'%.')+(j.passed?'':'<br><button class="btn btn-outline-k btn-sm mt-2" id="rq">Ulangi kuis</button>'));
  if(j.passed)unlock();else document.getElementById('rq').onclick=load}}
async function load(){const j=await post({action:'get'});if(j.error){box.innerHTML=note(false,esc(j.error));return}qs=j.questions;pass=j.pass;i=0;game?show():quiz()}
load();
})();
