<html>
<body>
<?php

        $con=mysqli_connect('127.0.0.1','custuser','phpwd','ParkingMaster');

    $namehold;
    if(isset($_POST['uname'])){
            $namehold = $_POST['uname'];
    }else{  
            $namehold=$usrname;
    }


        if(isset($_POST['Submit'])) {

                $event_Name = $_POST['eventName'];
                $day = $_POST['date'];
        $incorrectEventFlag;
        $incorrectDateFlag;

                if (!$con) {
                        die('Cannot connect'.mysqli_connect_error());
                }
                else {
            $sql="select count(*) from EVENT where EVENT.name = \"$event_Name\"";
            $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
            $r = mysqli_fetch_array($result);
            if($r['count(*)'] == 0) {//catching an incorrect event input
                $incorrectEventFlag = true;
            }

            else {
                $sql="select count(*) from EVENT where EVENT.start_Date <= \"$day\" AND EVENT.end_Date >= \"$day\"";
            $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
                $r = mysqli_fetch_array($result);
                if($r['count(*)'] == 0) { //catching an incorrect date-event input
                    $incorrectDateFlag = true;
                }
                else {
                    //display all garages that have available space on that day, distances to venues, and prices
                    $sql = "select Name as \"Garage Name\", fee as \"Fee ($)\", distance as \"Distance (mi)\" from
                    (select garage.garage_ID, garage.Name, garage.numSpaces, fee.fee, distance.distance
                        from venue, event, garage, fee, distance
                        where event.name=\"$event_Name\" and
                        venue.venue_ID = event.venue_ID and
                        event.event_ID = fee.event_ID and garage.garage_ID = fee.garage_ID
                        and venue.venue_ID = distance.venue_ID and garage.garage_ID = distance.garage_ID
                    ) as t1,
                    (select garage.garage_ID, ifnull(bookCount,0) as bookCount from
                        (select garage_ID, count(concat(reservation.user_ID, reservation.event_ID, reservation.reservation_Date)) as bookCount from reservation, event
                                where event.event_ID = reservation.event_ID
                                and reservation.reservation_Date = \"$day\"
                                and reservation.status = \"Active\"
                                group by garage_ID
                        ) as bookCount
                        right join garage
                        on  bookCount.garage_ID = garage.garage_ID
                    ) as t2
                    where t1.garage_ID = t2.garage_ID and t1.numSpaces > t2.bookCount
                    order by t1.distance";
                    $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
                    $flag1 = true;



                }
            }
                }
        }

        if(isset($_POST['Submit2'])) {

            $event_Name = $_POST['eventName'];
            $day = $_POST['date'];
    $garageName = $_POST['gName'];
    $incorrectEventFlag2;
    $incorrectDateFlag2;
    $incorrectGarageFlag;
    $successFlag;

            if (!$con) {
                    die('Cannot connect'.mysqli_connect_error());
            }
            else {
        //error checking
        $sql="select count(*) from EVENT where EVENT.name = \"$event_Name\"";
        $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
        $r = mysqli_fetch_array($result);
        if($r['count(*)'] == 0) {//catching an incorrect event input
            $incorrectEventFlag2 = true;
        }

        else {
            $sql="select count(*) from EVENT where EVENT.start_Date <= \"$day\" AND EVENT.end_Date >= \"$day\"";
            $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
            $r = mysqli_fetch_array($result);

            $sql="select event_ID from EVENT where EVENT.name=\"$event_Name\"";
            $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
            $r2 = mysqli_fetch_array($result);
            $eID = $r2['event_ID'];

            $sql="select count(*) from CANCELLATIONS where CANCELLATIONS.event_ID=$eID AND CANCELLATIONS.start_Date <= \"$day\" AND CANCELLATIONS.end_Date >= \"$day\"";
            $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
            $r3 = mysqli_fetch_array($result);
            if($r['count(*)'] == 0 OR $r3['count(*)'] > 0) { //catching an incorrect date for a real event
                $incorrectDateFlag2 = true;
            }
            else {
                $sql="select count(*) from GARAGE where GARAGE.name = \"$garageName\" ";
                $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
                $r = mysqli_fetch_array($result);
                if($r['count(*)'] == 0) { //catching an incorrect garage input
                    $incorrectGarageFlag = true;
                }
                else {
                      $successFlag = true;
                      //create reservation
                      $userID = $namehold;


                     // $user_ID = "mike13"; //DEBUGGING

                      $sql="select garage_ID from GARAGE where GARAGE.name=\"$garageName\"";
                      $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
                      $r = mysqli_fetch_array($result);
                      $gID = $r['garage_ID'];

                      $sql="select event_ID from EVENT where EVENT.name=\"$event_Name\"";
                      $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
                      $r = mysqli_fetch_array($result);
                      $eID = $r['event_ID'];

                      $sql="INSERT INTO RESERVATION values (\"$userID\", $gID, $eID, \"$day\", \"active\")";
                      $result = mysqli_query($con,$sql) or die(mysqli_connect_error());

                      $sql="select fee from fee where event_ID=$eID and garage_ID = $gID";
                      $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
                      $r = mysqli_fetch_array($result);
                      $fee = $r[0];

                    }
                }
            }
		}

	}
	
    if(isset($_POST['Submit3'])) {

        $event_Name = $_POST['eventName'];
        $day = $_POST['date'];
$garage_Name = $_POST['gName4'];
        if (!$con) {
                die('Cannot connect'.mysqli_connect_error());
        }
        else {
    $user = $namehold;

    $sql="select garage_ID from GARAGE where GARAGE.name = \"$garage_Name\" ";
    $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
    $r = mysqli_fetch_array($result);
    $gID = $r['garage_ID'];

    $sql="select event_ID from EVENT where EVENT.name = \"$event_Name\" and EVENT.start_Date <= \"$day\" AND EVENT.end_Date >= \"$day\"";
    $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
    $r = mysqli_fetch_array($result);
    $eID = $r['event_ID'];

    $sql = "select datediff(reservation_Date,curdate()) from RESERVATION where RESERVATION.user_ID=\"$user\" and RESERVATION.garage_ID=$gID AND RESERVATION.event_ID=$eID AND RESERVATION.reservation_Date=\"$day\";";
    $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
    $r = mysqli_fetch_array($result);
    $count = $r[0];

    if($count > 2) {
        $sql="update RESERVATION set status=\"cancelled\" where RESERVATION.user_ID=\"$user\" and RESERVATION.garage_ID=$gID AND RESERVATION.event_ID=$eID AND RESERVATION.reservation_Date = \"$day\"";
        $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
        $cancelFlag = true;
    }
    else {
        $dateFlag = true;
    }



    }
}


?>
<h3>Welcome, Customer</h3>
<h4>Find A Garage</h4>

<form name="form" action="/GrProject/Customer.php" method="POST">
        <label for="date">Event Date</label><br>
        <input type="DATE" name="date" id="date"><br>
        <label for="eventName">Event Name</label><br>
        <input type="text" name="eventName" id="eventName"><br>
        <input type="submit" name="Submit" class="button" value="Submit">
    <input type="hidden" id="uname" name="uname" value=<?php echo $namehold; ?> >
        </form>
<?php
        if(isset($incorrectEventFlag)) {
        Echo "Error: You entered an event that does not exist, please try again";
        }      
    if(isset($incorrectDateFlag)) {
        Echo "Error: The event and date you entered do not match, please try again with a different date";
    }
    if(isset($flag1)) {
        echo "Available Parking: <br>";
        While ($row = mysqli_fetch_array($result)) {
            print("$row[0]     $$row[1]     $row[2] miles");
            echo "<br>";

        }
    }
?>

<br><br><h4>Submit a Reservation</h4>
<form name="form" action="/GrProject/Customer.php" method="POST">
    <label for="date">Date</label><br>
        <input type="DATE" name="date" id="date"><br>
        <label for="eventName">Event Name</label><br>
        <input type="text" name="eventName" id="eventName"><br>
    <label for="gName">Garage Name</label><br>
        <input type="text" name="gName" id="gName"><br>
        <input type="submit" name="Submit2" class="button" value="Submit">
    <input type="hidden" id="uname2" name="uname" value=<?php echo $namehold; ?> >
        </form>

<?php
        if(isset($incorrectEventFlag2)) {
        Echo "Error: You entered an event that does not exist, please try again";
        }      
    if(isset($incorrectDateFlag2)) {
        Echo "Error: The event and date you entered do not match, please try again with a different date";
    }
    if(isset($incorrectGarageFlag)) {
        Echo "Error: The garage you entered is not valid. Try using the tool above to find a garage, then attempt to make a reservation again.";
    }
    if(isset($successFlag)) {
        Echo "Reservation has been made at $garageName for $event_Name on $day for $fee dollars. Resevation number: " . $day . $eID . $gID . $user_ID;
    }
?>
<br><br><h4>Cancel a Reservation</h4>
<form name="form" action="/GrProject/Customer.php" method="POST">
    <label for="date">Date</label><br>
        <input type="DATE" name="date" id="date"><br>
        <label for="eventName">Event Name</label><br>
        <input type="text" name="eventName" id="eventName"><br>
    <label for="gName">Garage Name</label><br>
        <input type="text" id="gName4" name="gName4"><br>
        <input type="submit" name="Submit3" class="button" value="Submit">
    <input type="hidden" id="uname3" name="uname" value=<?php echo $namehold; ?> >
        </form>
<?php
    if(isset($cancelFlag)) {
        Echo "Reservation cancelled for $event_Name on $day at $garage_Name";
    }
    if(isset($dateFlag)) {
        Echo "It is too close to the date of the event, you cannot cancel";
    }
?>

</body>
</html>
