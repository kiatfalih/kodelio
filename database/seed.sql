USE kodelio;
INSERT INTO learning_paths(slug,name,description,sort_order) VALUES('beginner','Beginner','HTML, CSS, dan JavaScript dari nol.',1),('intermediate','Intermediate','Advanced JavaScript, database, backend, API, dan aplikasi web.',2);
INSERT INTO modules(path_id,slug,name,sort_order) SELECT id,'html','HTML',1 FROM learning_paths WHERE slug='beginner';
INSERT INTO modules(path_id,slug,name,sort_order) SELECT id,'css','CSS',2 FROM learning_paths WHERE slug='beginner';
INSERT INTO modules(path_id,slug,name,sort_order) SELECT id,'javascript','JavaScript',3 FROM learning_paths WHERE slug='beginner';
INSERT INTO modules(path_id,slug,name,sort_order) SELECT id,'web-intermediate','Web Development Intermediate',1 FROM learning_paths WHERE slug='intermediate';
INSERT INTO levels(module_id,level_no,title,summary,sort_order)
SELECT m.id,v.n,v.t,v.s,v.n FROM (
SELECT 'html' k,1 n,'HTML Introduction' t,'Pengenalan HTML · Struktur dokumen · Tag dan element · Heading · Paragraph' s
UNION ALL SELECT 'html',2,'Text & Formatting','Formatting teks · Bold, italic, underline · Comment · List · Special characters'
UNION ALL SELECT 'html',3,'Links & Media','Hyperlink · Image · Audio · Video · Menggabungkan media'
UNION ALL SELECT 'html',4,'Table & Form','Tabel · Row dan data · Input · Textarea dan button · Form sederhana'
UNION ALL SELECT 'html',5,'HTML Challenge','Semantic HTML · Header dan footer · Section dan article · Struktur halaman · Proyek profil'
UNION ALL SELECT 'css',1,'CSS Introduction','Pengenalan CSS · Inline · Internal · External · Selector'
UNION ALL SELECT 'css',2,'Styling Basics','Text color · Background · Font · Text alignment · Border'
UNION ALL SELECT 'css',3,'Box Model','Margin · Padding · Border · Width dan height · Box model'
UNION ALL SELECT 'css',4,'Layout Design','Display · Position · Flexbox · CSS Grid · Layout halaman'
UNION ALL SELECT 'css',5,'CSS Challenge','Responsive design · Media query · Navbar · Card · Proyek website'
UNION ALL SELECT 'javascript',1,'JavaScript Introduction','Pengenalan · Menghubungkan JS · Variable · Data types · Console dan alert'
UNION ALL SELECT 'javascript',2,'Logic & Operator','Arithmetic · Comparison · Logical · If else · Switch case'
UNION ALL SELECT 'javascript',3,'Looping & Function','For · While · Do while · Function · Parameter dan return'
UNION ALL SELECT 'javascript',4,'Array & Object','Pengenalan array · Mengakses array · Array method · Object · Property object'
UNION ALL SELECT 'javascript',5,'Interactive JavaScript','DOM · Mengakses elemen · Event listener · Form validation · Proyek interaktif'
UNION ALL SELECT 'web-intermediate',1,'Advanced JavaScript','ES6 · Arrow function · Destructuring · Promise · Async/Await'
UNION ALL SELECT 'web-intermediate',2,'Database Development','Pengenalan MySQL · Database dan tabel · Primary dan foreign key · Relasi · JOIN dan CRUD'
UNION ALL SELECT 'web-intermediate',3,'Backend Development','Pengenalan backend · Koneksi database · Data form · Session dan autentikasi · CRUD'
UNION ALL SELECT 'web-intermediate',4,'API & Integration','Pengenalan API · REST API · JSON · Fetch API · Validasi dan keamanan'
UNION ALL SELECT 'web-intermediate',5,'Web Application Project','Perencanaan · Struktur database · Frontend-backend · Fitur aplikasi · Proyek aplikasi web'
) v JOIN modules m ON m.slug=v.k;
-- 5 step per level: Learn, Understand the Code, Code Practice, Mini Game, Final Quiz (isi konten dikerjakan di tahap 6)
INSERT INTO lessons(level_id,step_no,type,title,xp_reward)
SELECT l.id,s.n,s.t,CONCAT(s.lbl,': ',l.title),s.xp FROM levels l CROSS JOIN (
SELECT 1 n,'learn' t,'Learn' lbl,10 xp UNION ALL SELECT 2,'understand','Understand the Code',10 UNION ALL SELECT 3,'practice','Code Practice',20
UNION ALL SELECT 4,'minigame','Mini Game',20 UNION ALL SELECT 5,'quiz','Final Quiz',40) s;
INSERT INTO achievements(code,name,description,icon,xp_bonus) VALUES
('first_step','First Step','Menyelesaikan step pertama','bi-flag',10),('html_explorer','HTML Explorer','Menyelesaikan seluruh materi HTML','bi-code-slash',50),
('css_designer','CSS Designer','Menyelesaikan seluruh materi CSS','bi-palette',50),('js_starter','JavaScript Starter','Menyelesaikan seluruh materi JavaScript','bi-lightning-charge',50),
('perfect_quiz','Perfect Quiz','Nilai sempurna pada final quiz','bi-stars',30),('consistent_learner','Consistent Learner','Belajar 7 hari berturut-turut','bi-fire',40);
