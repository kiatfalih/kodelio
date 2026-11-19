(()=>{
const $=s=>document.querySelector(s),$$=s=>[...document.querySelectorAll(s)];
const home=$('#home'),flow=$('#flow'),dlg=$('#dlg'),app=$('#app');
const reduce=matchMedia('(prefers-reduced-motion: reduce)').matches;
function toast(m){const t=$('#toast');t.textContent=m;t.classList.add('on');clearTimeout(toast.h);toast.h=setTimeout(()=>t.classList.remove('on'),2400)}
function openFlow(mid,smooth,target){home.hidden=true;flow.hidden=false;
 const el=(target&&document.getElementById(target))||document.getElementById('m'+mid);
 if(el)el.scrollIntoView({block:target?'center':'start',behavior:smooth&&!reduce?'smooth':'auto'});}
$$('.kmod').forEach(b=>b.addEventListener('click',()=>{
 if(b.classList.contains('locked')){b.classList.remove('shake');void b.offsetWidth;b.classList.add('shake');toast(b.dataset.msg);return}
 if(home.classList.contains('leaving'))return;
 b.classList.add('pick');home.classList.add('leaving');
 setTimeout(()=>{home.classList.remove('leaving');openFlow(b.dataset.m,false)},reduce?0:450);}));
$('#toHome').onclick=()=>{flow.hidden=true;home.hidden=false;scrollTo(0,0);$$('.kmod').forEach(b=>b.classList.remove('pick'))};
document.addEventListener('click',e=>{
 if(e.target.closest('.cloud')){toast('Selesaikan level sebelumnya dulu.');return}
 const d=e.target.closest('.kdot');if(!d)return;
 if(d.dataset.state==='lock'){toast('Selesaikan step sebelumnya dulu.');return}
 $('#dT').textContent=d.dataset.lvl;$('#dS').textContent=d.dataset.label+' · +'+d.dataset.xp+' XP';
 $('#dGo').href='lesson.php?id='+d.dataset.id;$('#dGo').textContent=d.dataset.state==='done'?'Ulangi step':'Mulai step';dlg.showModal();});
$('#dX').onclick=()=>dlg.close();
const o=+app.dataset.open;if(o)openFlow(o,false,location.hash.slice(1));
})();
