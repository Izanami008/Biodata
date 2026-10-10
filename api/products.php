<?php
require __DIR__.'/common.php'; $method=$_SERVER['REQUEST_METHOD']??'GET';
if($method==='GET') { $q=$pdo->query('SELECT id,name,description,price,image,stock,created_at FROM products ORDER BY id DESC'); respond(['products'=>$q->fetchAll()]); }
require_admin(); $d=json_input();
if($method==='POST') { $name=trim($d['name']??'');$desc=trim($d['description']??'');$price=filter_var($d['price']??null,FILTER_VALIDATE_FLOAT);$image=trim($d['image']??'');$stock=filter_var($d['stock']??0,FILTER_VALIDATE_INT); if($name===''||$price===false||$price<0||$stock===false||$stock<0) respond(['error'=>'Nama, harga, dan stok tidak valid'],422);$s=$pdo->prepare('INSERT INTO products(name,description,price,image,stock) VALUES(?,?,?,?,?)');$s->execute([$name,$desc,$price,$image,$stock]);respond(['ok'=>true,'id'=>(int)$pdo->lastInsertId()],201); }
if($method==='PUT') { $id=(int)($d['id']??0);if($id<1)respond(['error'=>'ID produk tidak valid'],422);$s=$pdo->prepare('UPDATE products SET name=?,description=?,price=?,image=?,stock=? WHERE id=?');$s->execute([trim($d['name']??''),trim($d['description']??''),(float)($d['price']??0),trim($d['image']??''),max(0,(int)($d['stock']??0)),$id]);respond(['ok'=>true]); }
if($method==='DELETE') { $id=(int)($d['id']??0);$s=$pdo->prepare('DELETE FROM products WHERE id=?');$s->execute([$id]);respond(['ok'=>true]); }
respond(['error'=>'Metode tidak diizinkan'],405);
