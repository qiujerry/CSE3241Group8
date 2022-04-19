<!DOCTYPE html>
<html>
<body>

<?php

    if(isset($_POST['uname']) && isset($_POST['psw'])) {
        echo 'test';
    }else{
        $_POST['inc']='true';
        header("/login.php"); 
        exit;
    }

  ?>

</body>
</html>
