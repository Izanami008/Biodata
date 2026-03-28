<?php
session_start();

$username = "admin";
$password = "12345";

if(isset($_POST['login'])) {

    if($_POST['username'] == $username && $_POST['password'] == $password) {

        $_SESSION['admin'] = true;
        header("Location: admin.php");
        exit();

    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login Admin</title>

    <style>

        body {
            background: black;
            color: white;
            font-family: Arial;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .box {
            background: #111;
            padding: 30px;
            border-radius: 10px;
            width: 300px;
            text-align: center;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background: black;
            border: 1px solid #333;
            color: white;
        }

        button {
            margin-top: 15px;
            padding: 10px;
            width: 100%;
            background: #00ffcc;
            border: none;
            cursor: pointer;
        }

    </style>

</head>

<body>

<div class="box">

    <h2>Login Admin</h2>

    <?php if(isset($error)) echo $error; ?>

    <form method="POST">

        <input type="text" name="username" placeholder="Username" required>

        <input type="password" name="password" placeholder="Password" required>

        <button name="login">Login</button>

    </form>

</div>

</body>

</html>
