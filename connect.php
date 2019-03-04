
<?php
    $conn = new mysqli('localhost','root','','manager_member',3306);
    if  ($conn -> connect_error){
        $error = $conn -> connect_error;
        echo"<script>console.log($error)</script>";
    }else{
        echo"<script>console.log('Connected')</script>";
    }
?>