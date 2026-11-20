(()=>{const f=document.getElementById('ptForm');if(!f)return;document.documentElement.classList.add('js');
f.querySelectorAll('input').forEach(x=>x.required=false);
const qs=[...f.querySelectorAll('.pt-qw')],n=qs.length,bar=document.getElementById('ptBar'),lab=document.getElementById('ptLab'),
back=document.getElementById('ptBack'),next=document.getElementById('ptNext'),go=document.getElementById('ptGo');let i=0;
const chosen=()=>qs[i].querySelector('input:checked');
function sync(){const ok=!!chosen(),last=i===n-1;next.hidden=last;go.hidden=!last;next.disabled=!ok;go.disabled=!ok;bar.style.width=((i+(ok?1:0))/n*100)+'%'}
function show(k){i=k;qs.forEach((q,x)=>q.classList.toggle('on',x===k));lab.textContent='Soal '+(k+1)+' dari '+n;back.style.visibility=k?'visible':'hidden';sync()}
f.addEventListener('change',e=>{if(e.target.matches('input[type=radio]'))sync()});
next.onclick=()=>{if(chosen()&&i<n-1)show(i+1)};back.onclick=()=>{if(i>0)show(i-1)};
f.addEventListener('submit',e=>{if(!chosen()){e.preventDefault()}});
document.addEventListener('keydown',e=>{if(e.target.matches('textarea,select'))return;const opts=qs[i].querySelectorAll('input'),k='1234abcd'.indexOf(e.key.toLowerCase());
 if(k>-1&&opts[k%4]){opts[k%4].checked=true;sync()}else if(e.key==='Enter'&&chosen()){e.preventDefault();i<n-1?show(i+1):f.requestSubmit()}});
show(0)})();
