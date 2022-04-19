<?php
    $con=mysqli_connect('localhost','loginuser','phpwd','ParkingMaster');

    $usrname = "";
    include ("/Customer.php");

    if(isset($_POST['uname']) && isset($_POST['psw'])) {
        $lname = $_POST['uname'];
        $usrname = $_POST['uname'];
		$pw = $_POST['psw'];
	
		if (!$con) {	
			die('Cannot connect'.mysqli_connect_error());
		}
		else {
            $sql="select count(*) from CUSTOMER where CUSTORMER.user_ID = \"$lname\" AND CUSTOMER.password = \"$pw\"";
            $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
            $r = mysqli_fetch_array($result);
            if($r['count(*)'] == 1) {//catching an incorrect event input
                header("/Customer.php");
                exit;
            }

            $sql2="select count(*) from ADMINLOG where ADMINLOG.user_ID = \"$lname\" AND ADMINLOG.password = \"$pw\"";
            $result = mysqli_query($con,$sql2) or die(mysqli_connect_error());
            $r = mysqli_fetch_array($result);
            if($r['count(*)'] == 1) {//catching an incorrect event input
                $sql3="select adtype from ADMINLOG where ADMINLOG.user_ID = \"$lname\" AND ADMINLOG.password = \"$pw\"";
                $result = mysqli_query($con,$sql2) or die(mysqli_connect_error());
                $r = mysqli_fetch_array($result);
                $atype = $r[0];
                if($atype == 1){
                    header("/GarageAdmin.php");
                    exit;
                }elseif($atype == 2){
                    header("/EventAdmin.php");
                    exit;
                }
            }

            $_POST['inc']='true';
            header("/login.php");
            exit;
        }
    }else{
        $_POST['inc']='true';
        header("/login.php");
        exit;
    }

?> 
