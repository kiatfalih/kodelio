<?php require __DIR__.'/includes/bootstrap.php';
if($_SERVER['REQUEST_METHOD']==='POST'){csrf_check();$_SESSION=[];setcookie(session_name(),'',time()-3600,'/');session_destroy();}
redirect('index.php');
