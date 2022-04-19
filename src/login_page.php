<?php
    $con=mysqli_connect('127.0.0.1','aduser','phpwd','ParkingMaster');
    if(isset($_POST['uname']) && isset($_POST['psw'])) {
        $lname = $_POST['uname'];
                $pw = $_POST['psw'];

                if (!$con) {
                        die('Cannot connect'.mysqli_connect_error());
                }
                else {
            $sql="select count(*) from CUSTOMER where CUSTOMER.user_ID = \"$lname\" AND CUSTOMER.password = \"$pw\"";
            $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
            $r = mysqli_fetch_array($result);
            if($r[0] == 1) {
                $usrname = $_POST['uname'];
                include ("./Customer.php");
               // header("Location: /Customer.php");
                exit;
            }

            $sql2="select count(*) from ADMINLOG where ADMINLOG.user_ID = \"$lname\" AND ADMINLOG.password = \"$pw\"";
            $result2 = mysqli_query($con,$sql2) or die(mysqli_connect_error());
            $r2 = mysqli_fetch_array($result2);
            if($r2[0] == 1) {//catching an incorrect event input
                $sql3="select adType from ADMINLOG where ADMINLOG.user_ID = \"$lname\" AND ADMINLOG.password = \"$pw\"";
                $result3 = mysqli_query($con,$sql3) or die(mysqli_connect_error());
                $r3 = mysqli_fetch_array($result3);
                $atype = $r3[0];
                if($atype =='1'){
                   header("Location: /GarageAdmin.php");
                   exit;
                }elseif($atype == 2){
                    header("Location: /EventAdmin.php");
                    exit;
                }
            }

            $_POST['inc']='true';
            header("Location: /login.php");
            exit;
        }
    }else{
        $_POST['inc']='true';
        header("Location: /login.php");
        exit;
    }

?>