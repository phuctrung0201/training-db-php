
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css.bootstrap.min.css">
    <title>Document</title>
</head>
<?php
    include 'connect.php';
?>
<body style="height:100%; background-image: linear-gradient(#62c4f1,#a45ed3);  background-repeat: no-repeat;">
    <div class="container position-relative h-100">
        <div class="container w-50">
            <?php
                if(isset($_POST['submit'])){
                    $check = $conn->prepare('insert into user values(?,?,?,?,?,?)');
                    $username = $_POST['username'];
                    $id = $conn->query('select * from user')->num_rows;
                    $password = $_POST['password'];
                    $name = $_POST['name'];
                    $old = $_POST['old'];
                    $gender = $_POST['gender'];
                    if( $check->bind_param('isssis',$id,$username,$password,$name,$old,$gender) && $check->execute()){
                        header('location: login.php');
                    }else{
                        echo "<div class='alert alert-danger text-center'>
                        Sign up is fail!
                      </div>";
                    }
                }
            ?>
        </div>
        <div class="border border-warning shadow-lg rounded position-absolute w-50 h-50 bg-secondary text-warning" style="top: 50%; left:50%; transform:translate(-50%,-50%);">
            <div class="text-center mt-2">
                <h1>Register</h1>
            </div>
            <div class='container'>
                <form action="register.php" method='POST'>
                    <label for="username">Username</label>
                    <input type="text" class="form-control rounded-0 border-secondary" name='username' id="username" placeholder='Username'/>
                    <label for="password">Password</label>
                    <input type="password" class="form-control rounded-0 border-secondary" name='password' id="password" placeholder='Password'/>
                    <label for="name">Name</label>
                    <input type="text" class="form-control rounded-0 border-secondary" name='name' id="name" placeholder='Name'>
                    <div class="row">
                        <div class="col-md">
                        <div>Old</div>
                        <input type="number" min='1' class="form-control rounded-0 border-secondary" name='old' placeholder='Old'>
                        </div>
                        <div class="col-md">
                        <div>Gender</div>
                        <select class="custom-select rounded-0 border-secondary" name='gender'>
                            <option value='m'>Male</option>
                            <option value='f'>Female</option>
                        </select>
                        </div>
                    </div>
                    <div class="text-center pt-2">
                        <input type="submit" name='submit' value="Register" class='btn btn-warning'/>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        
    </script>
</body>
</html>