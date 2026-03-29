<?php
session_start();

if(isset($_POST['login'])){

    $user = $_POST['user'];
    $pass = $_POST['pass'];

    if($user == "admin" && $pass == "123"){

        $_SESSION['login'] = true;
        header("Location: admin.php");

    } else {

        $error = "Username atau password salah";

    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Login Admin</title>

<style>

body{
    background:#0f172a;
    color:white;
    font-family:Arial;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    background:#1e293b;
    padding:30px;
    border-radius:10px;
}

input{
    padding:10px;
    margin:10px 0;
    width:100%;
}

button{
    padding:10px;
    width:100%;
    background:#00ffcc;
    border:none;
}

.error{
    color:red;
}

</style>

</head>
<body>

<div class="box">

<h2>Login Admin</h2>

<?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

<form method="post">

<input type="text" name="user" placeholder="Username">

<input type="password" name="pass" placeholder="Password">

<button name="login">Login</button>

</form>

</div>

</body>
</html>
