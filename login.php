<!DOCTYPE html>
<html lang="en" style="height:100%;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <title>Login</title>
</head>
<?php
    $username ='';
    $password ='';
    if(isset($_POST['submit'])){
        include 'connect.php';
        $check = $conn->prepare('select  * from user where username = ? and password = ?');
        $check->bind_param('ss',$username,$password);
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $login = false;
        if($check->execute()){
            $user = $check->get_result();
            $login = $user->num_rows != 0;
        }
        if($login){
            session_start();
            $_SESSION['usern'] = $username;
            $_SESSION['passw'] = $password;
            $id = $user->fetch_assoc()['id'];
            $role = $conn->query("select * from role where id='$id'");
            $_SESSION['role'] = $role->fetch_assoc()['role'];
            header('location: manager.php');
        }
    }
?>
<body class='bg-light h-100'>
    <div class='pt-5 container card' style='max-width:350px; position: relative; top:50%; transform: translateY(-50%);'>
        <h1 class='text-center' >Login</h1>
        <?php
        if(isset($login) && !$login){
            echo"<div class='alert alert-danger'>Username and password is invalid</div>";
        }
        ?>
        <form action="login.php" method="post">
        <label for="username">Username</label>
        <input type="text" class='form-control mb-2' name="username" id="username"/>
        <label for="password">Password</label>
        <input type="password" class='form-control mb-2' name="password" id="password"/>
        <div class='text-center mb-3'>
        <input type="submit" value="login" name='submit' class='btn btn-outline-success btn-lg'/>
        <a href="register.php" class='d-block text-success'>Register</a>
        </div>
        </form>

    </div>
</body>
</html>