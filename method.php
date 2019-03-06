<form action="manager.php" method='post'>

<!--*****************Add*************************-->
<div class="modal" id="add">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add member</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
            <label >Username</label>
            <input type="text" class='form-control' name="usern"/>
            <label >Password</label>
            <input type="password" class='form-control' name="passw" />
            <label>Name</label>
            <input type="text" class='form-control' name="name" />
            <label >Age</label>
            <input type="number" class='form-control' name="age" />
            <label >Gender</label>
            <select name="gender" class='form-control custom-select'>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
            <?php
                if ($_SESSION['role'] == 'manager')
                echo "
                <label >Role</label>
            <select name='role' class='form-control custom-select'>
                <option value='manager'>Manager</option>
                <option value='admin'>Admin</option>
                <option value='member'>Member</option>
            </select>
                ";
            ?>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
            <input type="submit" name='method' class ='btn btn-outline-success'value="Add"/>
        </div>
    </div>
  </div>
</div>
</form>


<form action="manager.php" method='post'>
<!--*************************Remove*****************************-->
<div class="modal" id="remove">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Remove member</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
        <div class="modal-body">
            <label >Username</label>
            <input type="text" class='form-control' name="usern" />
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
            <input type="submit" name='method' class ='btn btn-outline-success'value="Remove"/>
        </div>
    </div>
  </div>
</div>
</form>





<!--**********************Permiss*****************************-->

<?php
    if($_SESSION['role'] == 'manager'){
        echo "<form action='manager.php' method='post'>
        <div class='modal' id='permiss'>
          <div class='modal-dialog'>
            <div class='modal-content'>
        
              <!-- Modal Header -->
              <div class='modal-header'>
                <h4 class='modal-title'>Permiss member</h4>
                <button type='button' class='close' data-dismiss='modal'>&times;</button>
              </div>
        
              <!-- Modal body -->
                <div class='modal-body'>
                    <label >Username</label>
                    <input type='text' class='form-control' name='usern'/>
                    <select name='role' class='form-control custom-select w-25 mt-3'>
                        <option value='manager'>Manager</option>
                        <option value='admin'>Admin</option>
                        <option value='member'>Member</option>
                    </select>
                </div>
                
                <!-- Modal footer -->
                <div class='modal-footer'>
                    <input type='submit' name='method' class ='btn btn-outline-success' value='Permiss'/>
                </div>
            </div>
          </div>
        </div>
        </form>";
    }
    if(isset($_POST['method'])){
        $complete = true;

        //--------------------------Add--------------------//
        if($_POST['method'] == 'Add'){
            $checkUser = $conn->prepare('insert into user(username,password) values(?,?)');
            $usern = $_POST['usern'];
            $passw = md5($_POST['passw']);
            $name = $_POST['name'];
            $age = $_POST['age'];
            $gender = $_POST['gender'];
            $role = $_SESSION['role'] == 'manager'?$_POST['role']:'member';
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
        }

        //-------------------------Remove-----------------//
        if($_POST['method'] == 'Remove'){
            $permiss = '';
            if($_SESSION['role'] == 'admin') {
                $permiss = "and id in (select id from role where role = 'member')";
            }
            $usern = $_POST['usern'];
            $remove = $conn->prepare("delete from user where username = ? ".$permiss);
            $remove->bind_param('s',$usern);
            if(!($remove->execute())){
                $complete = false;
            }
        }

        //------------------------Permiss---------------//
        if($_POST['method'] == 'Permiss' && $_SESSION['role'] == 'manager'){
            $usern = $_POST['usern'];
            $role = $_POST['role'];
            $findId = $conn->prepare("select * from user where username = ?");
            $findId->bind_param('s',$usern);
            if(!($findId->execute() && trim($usern) != '')){
                $complete = false;
            } else {
                $recordId = $findId->get_result();
                $id = $recordId->fetch_assoc()['id'];
                $updateRole = $conn->prepare("update role set role = ? where id = ?");
                $updateRole->bind_param('si',$role,$id);
                if(!($updateRole->execute())){
                    $complete = false;
                }
            }
        }




        //------------------------Alert-----------------//
        $alert = $complete?'Complete!!!':'Uncomplete!!!';
        $alert = "<script>alert('$alert');</script>";
        echo $alert;
    }
?>