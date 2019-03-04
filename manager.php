<!DOCTYPE html>
<html lang="en" style='height:100%;'>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <title>Manager</title>
</head>
<body class='h-100 bg-light'>
    <div class="container-fluid">
    <?php
     session_start();
     if(isset($_SESSION['usern'])){
         include 'connect.php';
        if($_SESSION['role'] == 'manager' || $_SESSION['role'] == 'admin') {
            $usern = $_SESSION['usern'];
            $infoMem = $conn->query("select * from user left join info on user.id = info.id");
            $mem = $infoMem->fetch_assoc();
            var_dump($mem);
        }
    }
    ?>
    </div>
</body>
</html>