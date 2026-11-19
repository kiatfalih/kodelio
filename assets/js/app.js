// Tampilkan/sembunyikan password
document.querySelectorAll('[data-toggle-pw]').forEach(b=>b.addEventListener('click',()=>{
 const i=document.querySelector(b.dataset.togglePw);i.type=i.type==='password'?'text':'password';
 b.querySelector('i').className=i.type==='password'?'bi bi-eye':'bi bi-eye-slash';}));
// Indikator kekuatan password
document.querySelectorAll('[data-strength]').forEach(inp=>{
 const bar=document.querySelector(inp.dataset.strength);
 inp.addEventListener('input',()=>{const v=inp.value;
  let s=0;if(v.length>=8)s++;if(/[A-Za-z]/.test(v)&&/\d/.test(v))s++;if(v.length>=12)s++;if(/[^A-Za-z0-9]/.test(v))s++;
  bar.style.width=(s*25)+'%';bar.style.background=['#ef4444','#ef4444','#f59e0b','#22c55e','#16a34a'][s];});});
// Validasi form sisi klien (server tetap memvalidasi ulang)
document.querySelectorAll('form.needs-validation').forEach(f=>{
 const m=f.querySelector('[data-match]');
 const chk=()=>{if(m)m.setCustomValidity(m.value===document.querySelector(m.dataset.match).value?'':'beda');};
 f.addEventListener('input',chk);
 f.addEventListener('submit',e=>{chk();if(!f.checkValidity()){e.preventDefault();e.stopPropagation();}f.classList.add('was-validated');});});
// Navbar bayangan saat scroll + animasi muncul
const nav=document.querySelector('.k-nav');
if(nav)addEventListener('scroll',()=>nav.classList.toggle('scrolled',scrollY>8),{passive:true});
const io='IntersectionObserver' in window?new IntersectionObserver(es=>es.forEach(x=>{if(x.isIntersecting){x.target.classList.add('in');io.unobserve(x.target);}}),{threshold:.12}):null;
document.querySelectorAll('.reveal').forEach(el=>io?io.observe(el):el.classList.add('in'));
