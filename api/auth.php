<?php
require __DIR__.'/common.php';
$action=$_GET['action'] ?? '';
if ($action==='me') { respond(['authenticated'=>!empty($_SESSION['user_id']),'id'=>$_SESSION['user_id']??null,'username'=>$_SESSION['username']??null,'role'=>$_SESSION['role']??null]); }
if ($action==='logout') { $_SESSION=[]; session_destroy(); respond(['ok'=>true]); }
ensure_method(['POST']); $d=json_input();
if ($action==='register') {
 $name=trim($d['fullname']??''); $username=trim($d['username']??''); $email=trim($d['email']??''); $password=$d['password']??'';
 if ($name===''||$username===''||!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($password)<8) respond(['error'=>'Isi semua data dengan benar. Password minimal 8 karakter.'],422);
 try {$q=$pdo->prepare('INSERT INTO users(fullname,username,email,password_hash,role) VALUES(?,?,?,?,\'user\')');$q->execute([$name,$username,$email,password_hash($password,PASSWORD_DEFAULT)]);} catch(PDOException $e){ if($e->getCode()==='23000') respond(['error'=>'Username atau email sudah digunakan'],409); throw $e; }
 respond(['ok'=>true],201);
}
if ($action==='login') {
 $username=trim($d['username']??''); $password=$d['password']??'';
 $q=$pdo->prepare('SELECT id,fullname,username,email,password_hash,role FROM users WHERE username=? OR email=? LIMIT 1');$q->execute([$username,$username]);$u=$q->fetch();
 if(!$u||!password_verify($password,$u['password_hash'])) respond(['error'=>'Username/email atau password salah'],401);
 session_regenerate_id(true); $_SESSION['user_id']=(int)$u['id'];$_SESSION['username']=$u['username'];$_SESSION['role']=$u['role'];
 unset($u['password_hash']); respond(['ok'=>true,'user'=>$u]);
}
respond(['error'=>'Aksi tidak dikenal'],404);
