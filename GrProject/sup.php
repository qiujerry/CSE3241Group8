
<?php 
    $con=mysqli_connect('127.0.0.1','aduser','phpwd','ParkingMaster');
    if(isset($_POST['uname']) && isset($_POST['psw'])&&isset($_POST['pn']) && isset($_POST['name'])){
        $lname = $_POST['uname'];
        $pw = $_POST['psw'];
        $n = $_POST['name'];
        $p = $_POST['pn'];

        $sql="select count(*) from CUSTOMER where CUSTOMER.user_ID = \"$lname\"";
        $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
        $r = mysqli_fetch_array($result);
        if($r[0] == 0) {
            $sql2 = "insert into CUSTOMER (name, user_ID, password, phoneNumber) Values (\"$n\", \"$lname\", \"$pw\",\"$p\")";
            mysqli_query($con,$sql2);
            header("Location: /login.php");
            exit;
        }else{
            $_POST['inc'] = 'true';
        }
    }

?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family: Arial, Helvetica, sans-serif;}
form {border: 3px solid #f1f1f1;}

input[type=text], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

button {
  background-color: #04AA6D;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

button:hover {
  opacity: 0.8;
}

.cancelbtn {
  width: auto;
  padding: 10px 18px;
  background-color: #f44336;
}

.imgcontainer {
  text-align: center;
  margin: 24px 0 12px 0;
}

img.avatar {
  width: 40%;
  border-radius: 50%;
}

.container {
  padding: 16px;
}

span.psw {
  float: right;
  padding-top: 16px;
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
  span.psw {
     display: block;
     float: none;
  }
  .cancelbtn {
     width: 100%;
  }
}
</style>
</head>
<body>

<h2>Parking Master Sign Up</h2>


<form action="/sup.php" method="POST">
  <div class="imgcontainer">
    <!-- <img src="img_avatar2.png" alt="Avatar" class="avatar"> -->
    <p>Parking Master Sign Up</p>
  </div>

  <div class="container">
    <label for="uname"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="uname" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>

    <label for="name"><b>Name</b></label>
    <input type="text" placeholder="Enter Name" name="name" required>

    <label for="pn"><b>Phone Number</b></label>
    <input type="text" placeholder="Enter Phone Number" name="pn" required>

        
    <button type="submit">Sign Up</button>

  </div>

</form>

<div>
  <?php
    if(isset($_POST['inc'])) {
      echo "Username exists";
    }

  ?>

</div>

</body>
</html>