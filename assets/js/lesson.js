(()=>{const S=window.LS,$=id=>document.getElementById(id);
document.querySelectorAll('.ex-list li').forEach(li=>li.onclick=()=>{document.querySelectorAll('.hl').forEach(x=>x.classList.remove('hl'));li.classList.add('hl');document.querySelectorAll('.code-ex span[data-i="'+li.dataset.i+'"]').forEach(x=>x.classList.add('hl'))});
const ta=$('code');let cm=null;const START=ta?ta.value:'';
const val=()=>cm?cm.getValue():ta.value,run=()=>{$('pv').srcdoc=val()};
if(ta){
 if(window.CodeMirror)cm=CodeMirror.fromTextArea(ta,{mode:'htmlmixed',lineNumbers:true,theme:'dracula',indentUnit:2,tabSize:2,lineWrapping:true,extraKeys:{'Ctrl-Enter':run}});
 else ta.addEventListener('keydown',e=>{if(e.key==='Tab'){e.preventDefault();ta.setRangeText('  ',ta.selectionStart,ta.selectionEnd,'end')}else if(e.key==='Enter'&&e.ctrlKey)run()});
 $('run').onclick=run;$('reset').onclick=()=>{cm?cm.setValue(START):ta.value=START;run()};run();
}
const done=$('done');
if(done)done.onclick=async()=>{done.disabled=true;const f=new FormData();f.append('csrf',S.csrf);f.append('id',S.id);if(ta)f.append('code',val());
 let j;try{j=await(await fetch('lesson_done.php',{method:'POST',body:f})).json()}catch(e){j={ok:false,msg:'Gagal menghubungi server.'}}
 const box=document.createElement('div');box.className='alert alert-'+(j.ok?'success':'warning')+' mb-0';box.textContent=j.msg+(j.ok&&j.xp?' +'+j.xp+' XP':'');
 $('fb').replaceChildren(box);
 if(j.ok){done.textContent='Selesai';const n=$('next');n.href=j.next?'lesson.php?id='+j.next:'path.php?p='+encodeURIComponent(S.slug);n.classList.remove('disabled')}else done.disabled=false};
})();
