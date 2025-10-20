<?php
session_start();
require '../config/config.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $u=$_POST['username']; $p=$_POST['password'];
  if($u==='admin' && $p==='admin2006'){ $_SESSION['user']=['username'=>'admin','role'=>'admin','id'=>0]; header('Location: dashboard.php'); exit; }
}
?>
<!doctype html><html><head><meta charset='utf-8'><title>Admin Login</title></head><body>
<form method="post"><input name="username"><input name="password"><button>Login</button></form>
</body></html>
