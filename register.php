<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <title>Document</title>
</head>
<?php
    if(isset($_POST['register'])){
        include 'connect.php';
        $complete = true;
            $checkUser = $conn->prepare('insert into user(username,password) values(?,?)');
            $usern = $_POST['usern'];
            $passw = md5($_POST['passw']);
            $name = $_POST['name'];
            $age = $_POST['age'];
            $gender = $_POST['gender'];
            $role = 'member';
            $checkUser->bind_param('ss',$usern,$passw);
            if( trim($usern) != '' && trim($passw) != '' && $checkUser->execute()){
                $id = $checkUser->insert_id;
                $checkInfo = $conn->prepare('insert into info values(?,?,?,?)');
                $checkInfo->bind_param('isis',$id,$name,$age,$gender);
                $checkRole = $conn->prepare('insert into role values(?,?)');
                $checkRole->bind_param('is',$id,$role);
                if (!(trim($name) != '' && $checkInfo->execute() && $checkRole->execute())) {
                    $complete = false;
                    $conn->query("delete from user where id = $id");
                }
            } else {
                $complete = false;
            }
            if ($complete) {
                header('location: login.php');
            }else{
                echo "<script>alert('Your infomation is invalid');</script>";
            }
    }
?>
<body class ='bg-light'>
    <div class="container w-50 py-5">
        <div class="card p-4">
        <h1 class="text-center">Register</h1>
        <form action="register.php" method='post'>
        <label >Username</label>
            <input type="text" class='form-control' name="usern"/>
            <label >Password</label>
            <input type="password" class='form-control' name="passw" />
            <label>Name</label>
            <input type="text" class='form-control' name="name" />
            <div class="row">
                <div class="col-sm">
                    <label >Age</label>
                    <input type="number" class='form-control' name="age" />
                </div>
                <div class="col-sm">
                    <label >Gender</label>
                    <select name="gender" class='form-control custom-select'>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
            </div>
            <hr/>
            <div class="text-center">
            <input type="submit" name='register' class='btn btn-outline-info' value="Register"/>
            </div>
        </form>
        </div>
    </div>
</body>
</html>