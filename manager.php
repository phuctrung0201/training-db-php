<!DOCTYPE html>
<html lang="en" style='height:100%;'>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <title>Manager</title>
</head>
<?php
session_start();
if(isset($_GET['logOut'])){
    session_unset();
    session_destroy();
    header('location: login.php');
}
?>
<body class='h-100 bg-light'>
    <div class="navbar navbar-dark bg-success justify-content-between fixed-top">
        <H1 class='text-light'><?php echo $_SESSION['role'] != 'member' ? 'Manager':'Your account'?></H1>
        <div class="btn-group">
            
            
            <?php 
            if ($_SESSION['role'] != 'member'){
                echo "<button class='btn btn-outline-light' data-toggle='modal' data-target='#add'>Add</button>
                <button class='btn btn-outline-light' data-toggle='modal' data-target='#remove'>Remove</button>";
            }
            if($_SESSION['role'] == 'manager' ) echo "<button class='btn btn-outline-light' data-toggle='modal' data-target='#permiss'>Permiss</button>";
            ?>
            
        </div>
        <form action="manager.php" method='get'>
            <input class='btn btn-warning' type="submit" name='logOut' value="Log out"/>
        </form>
    </div>
    <?php
    if(isset($_SESSION['usern'])){
        include 'connect.php';
        if($_SESSION['role'] == 'manager' || $_SESSION['role'] == 'admin') {
            include 'method.php';
        }
    }
    ?>
    <div class="container-fluid position-relative h-100">
        <div class="row position-relative" style='top:50%; transform : translateY(-50%);'>
            <?php
        if(isset($_SESSION['usern'])){
            if($_SESSION['role'] == 'manager' || $_SESSION['role'] == 'admin') {
                $page = 1;
                if(isset($_GET['page'])){
                    $page = $_GET['page'];
                } 
                $usern = $_SESSION['usern'];
                $infoMem = $conn->query("select * from user left join info on user.id = info.id left join role on user.id = role.id");

                //display record
                $index = 0;
                $display = '';
                $mems = $infoMem->fetch_all();
                $pageLength = (sizeof($mems)/5 +(sizeof($mems)%5 == 0 ? 0 : 1));
                if(isset($_GET['navigation'])){
                    $page = $_GET['navigation'] == 'Last' ? $pageLength : 1;
                }
                foreach ($mems as $mem){
                    $display = '';
                    $id = $mem[0];
                    $usern = $mem[1];
                    $passw = $mem[2];
                    $name = $mem[4];
                    $age = $mem[5];
                    $gender = $mem[6];
                    $role = $mem[8];

                    if ($index >= $page*5) break;
                    if ($index >= ($page - 1)*5){
                        $display = "
                            <div>Id: $id </div>
                            <div>Username: $usern </div>
                            <div>Password: $passw </div>
                            <div>Name: $name </div>
                            <div>Age: $age </div>
                            <div>Gender: $gender </div>
                            <div>Role: $role </div>
                        ";
                        echo "<div class='col-md mx-3 card'>$display</div>";
                    }
                    $index ++;
                }
            } else {
            $infoMem = $conn->query("select * from user left join info on user.id = info.id left join role on user.id = role.id");
            $display = '';
            $mem = $infoMem->fetch_all()[0];
            $id = $mem[0];
            $usern = $mem[1];
            $passw = $mem[2];
            $name = $mem[4];
            $age = $mem[5];
            $gender = $mem[6];
            $role = $mem[8];
            $display = "
                            <div>Id: $id </div>
                            <div>Username: $usern </div>
                            <div>Password: $passw </div>
                            <div>Name: $name </div>
                            <div>Age: $age </div>
                            <div>Gender: $gender </div>
                            <div>Role: $role </div>
                        ";
                        echo "<div class='col-md mx-3 card'>$display</div>";
        }
        } 
            ?>
        </div>
        <form action="manager.php" class='position-absolute' style='bottom:0; left:50%; transform: translateX(-50%);' >
            <ul class="pagination">
                <?php
                if($_SESSION['role'] != 'member'){
                    if($pageLength > 5){
                        echo "<li class='page-item'>
                        <input class='page-link' name='navigation' type='submit' value='First'/>
                        </li>";
                    }
                    if($page <= 3) {
                        $pageStart = 1;
                        $pageEnd = $pageLength < 5 ? $pageLength : 5;
                    } elseif ($page > ($pageLength - 2)) {
                        $pageEnd = $pageLength;
                        $pageStart = $pageEnd -4;
                    } else {
                        $pageStart = $page - 2;
                        $pageEnd = $page + 2;
                    }
                    for ($pageInd = $pageStart; $pageInd <= $pageEnd; $pageInd++){
                        $active = $page == $pageInd?'active':''; 
                        echo "<li class='page-item $active'><input class='page-link' name='page' type='submit' value='$pageInd'></li>";
                    }
                    if($pageLength > 5){
                        echo "<li class='page-item'>
                        <input class='page-link' name='navigation' type='submit' value='Last'/>
                        </li>";
                    }
                }
                ?>
            </ul>
        </form>
    </div>
    
    <script src="./js/jquery-3.3.1.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
</body>
</html>