<html>
<body> 
<?php 
    $con=mysqli_connect('localhost','root','*******','ParkingMaster');
	if(isset($_POST['Submit'])) {
		if (!$con) {	
			die('Cannot connect'.mysqli_connect_error());
		}
		else {
            $addEventFlag = true;
			$eventName = $_POST['eName'];
            $eventID = $_POST['eID'];
            $venueID = $_POST['vID'];
            $start_date = $_POST['startDate'];
            $end_date = $_POST['endDate'];

            //must add to existing venue
            $sql = "INSERT INTO EVENT values ($eventID, $venueID, \"$eventName\", \"$start_date\", \"$end_date\")";
            $result = mysqli_query($con,$sql) or die(mysqli_connect_error());

		} 	
	}
    
    if(isset($_POST['Submit2'])) {
		if (!$con) {	
			die('Cannot connect'.mysqli_connect_error());
		}
		else {
            $changeFeeFlag = true;
			$fee = $_POST['fee'];
            $eventID = $_POST['eID'];
            $garageID = $_POST['gID'];
                //check to see if fee for that set of venue garage and event exists, if yes UPDATE, if no INSERT
            $sql = "select count(*) from FEE where FEE.garage_ID=$garageID AND FEE.event_ID=$eventID";
            $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
            $r = mysqli_fetch_array($result);
            if($r['count(*)'] > 0) {
                $updateFlag = true;
                $sql = "update FEE set fee=$fee where FEE.garage_ID=$garageID AND FEE.event_ID=$eventID";
                $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
            }
            else {
                $insertFlag = true;
                $sql = "INSERT INTO FEE values ($garageID, $eventID, $fee)";
                $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
            }
            $sql = "Select name from GARAGE where GARAGE.garage_ID=$garageID";
            $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
            $r = mysqli_fetch_array($result);
            $garageName = $r['name'];
			} 	
	}
    
    if(isset($_POST['Submit3'])) {
		if (!$con) {	
			die('Cannot connect'.mysqli_connect_error());
		}
		else {
            $removeEventFlag = true;
			$eID = $_POST['eID'];
            
            //cancel reservations for that event
            $sql = "update RESERVATION set RESERVATION.status=\"cancelled\" where RESERVATION.event_ID = $eID";
            $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
            
            $sql = "select name from EVENT where EVENT.event_ID=$eID";
            $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
            $r = mysqli_fetch_array($result);
            $eventName = $r['name'];
            
            $sql = "select start_Date, end_Date from EVENT where EVENT.event_ID=$eID";
            $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
            $r = mysqli_fetch_array($result);
            $sDate = $r['start_Date'];
            $eDate = $r['end_Date'];


            //insert into cancellations table
            $sql="INSERT INTO CANCELLATIONS values ($eID, \"$sDate\", \"$eDate\")";
            $result = mysqli_query($con,$sql) or die(mysqli_connect_error());

		}
	}
    
    
    if(isset($_POST['Submit4'])) {
		if (!$con) {	
			die('Cannot connect'.mysqli_connect_error());
		}
		else {
            $removeEventByDateFlag = true;
			$eID = $_POST['eID'];
            $eDate = $_POST['eDate'];
            $sql = "update RESERVATION set RESERVATION.status=\"cancelled\" where RESERVATION.event_ID = $eID AND RESERVATION.reservation_Date=\"$eDate\"";
            $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
            
            $sql = "select name from EVENT where EVENT.event_ID=$eID";
            $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
            $r = mysqli_fetch_array($result);
            $eventName = $r['name'];

            //insert date and event into cancellations table
            $sql = "INSERT INTO CANCELLATIONS values ($eID, \"$eDate\", \"$eDate\")";
            $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
		}
	}

    if(isset($_POST['Submit5'])) {
        if (!$con) {	
            die('Cannot connect'.mysqli_connect_error());
        }
        else {
            $sum = 0;
            $revenueByEventFlag = true;
            $eID = $_POST['eID'];
            $sDate = $_POST['sDate'];
            $eDate = $_POST['eDate'];

            $sql = "select fee from FEE, RESERVATION where FEE.event_ID = RESERVATION.event_ID AND RESERVATION.event_ID = $eID AND RESERVATION.garage_ID = FEE.garage_ID AND RESERVATION.reservation_Date > \"$sDate\" AND RESERVATION.reservation_Date < \"$eDate\"  ";
            $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
            While ($row = mysqli_fetch_array($result)) {
                $sum = $sum + $row['fee'];
            }
        }
    }

    if(isset($_POST['Submit6'])) {
        if (!$con) {	
            die('Cannot connect'.mysqli_connect_error());
        }
        else {
            $sum = 0;
            $revenueByGarageFlag = true;
            $gID = $_POST['gID'];
            $sDate = $_POST['sDate'];
            $eDate = $_POST['eDate'];

            $sql = "select fee from FEE, RESERVATION where FEE.garage_ID = RESERVATION.garage_ID AND RESERVATION.garage_ID = $gID AND RESERVATION.event_ID = FEE.event_ID AND RESERVATION.reservation_Date > \"$sDate\" AND RESERVATION.reservation_Date < \"$eDate\"  ";
            $result = mysqli_query($con,$sql) or die(mysqli_connect_error());
            While ($row = mysqli_fetch_array($result)) {
                $sum = $sum + $row['fee'];
            }

        }
    }
    
?>
<h3>What would you like to do, Event Administrator?</h3>
<h4>Add Event</h4>
<form name="form" action="EventAdmin.php" method="POST">
	<label for="eName">Event Name</label><br>
	<input type="text" name="eName" id="eName"><br>
    <label for="eID">Event ID</label><br>
	<input type="int" name="eID" id="eID"><br>
    <label for="vID">Venue ID</label><br>
	<input type="int" name="vID" id="vID"><br>
    <label for="startDate">Event Start Date</label><br>
	<input type="DATE" name="startDate" id="startDate"><br>
    <label for="endDate">Event End Date</label><br>
	<input type="DATE" name="endDate" id="endDate"><br>
	<input type="submit" name="Submit" class="button" value="Submit">
	</form>
<?php
    if(isset($addEventFlag)) {
        Echo "Event " . $eventName . " has been added";
    }
?>

<h4>Set Parking Fees For an Event</h4>
<form name="form" action="EventAdmin.php" method="POST">
    <label for="eID">Event ID</label><br>
	<input type="int" name="eID" id="eID"><br>
    <label for="gID">Garage ID</label><br>
	<input type="int" name="gID" id="gID"><br>
    <label for="fee">Fee</label><br>
	<input type="double" name="fee" id="fee"><br>
	<input type="submit" name="Submit2" class="button" value="Submit">
	</form>
    
<?php
    if(isset($updateFlag)) {
        Echo "Fee at " . $garageName . " has been updated to $" . $fee; 
    }
    if(isset($insertFlag)) {
        Echo "Fee at " . $garageName . " has been created and set to $" . $fee;
    }
?>

<h4>Remove Event</h4>
<p>This will remove the event across all days and will cancel all parking reservations for this event</p><br>
<form name="form" action="EventAdmin.php" method="POST">
    <label for="eID">Event ID</label><br>
	<input type="int" name="eID" id="eID"><br>
	<input type="submit" name="Submit3" class="button" value="Submit">
	</form>
    <?php
    if(isset($removeEventFlag)) {
        Echo "Event " . $eventName . " has been cancelled for \"$eDate\". All parking reservations for " . $eventName . "  on \"$eDate\" have been cancelled";
    }
?>    

<h4>Remove Event for Specific Date</h4>
<p>This will remove the event for one specified day</p>
<p>And will cancel all parking reservations for this event for the day specified</p>
<form name="form" action="EventAdmin.php" method="POST">
    <label for="eID">Event ID</label><br>
	<input type="int" name="eID" id="eID"><br>
    <label for="eDate">Event Date</label><br>
	<input type="DATE" name="eDate" id="eDate"><br>
	<input type="submit" name="Submit4" class="button" value="Submit">
	</form> 
<?php
    if(isset($removeEventByDateFlag)) {
        Echo "$eventName has been deleted for \"$eDate\"";
    }
?>

<br><br><h4>Find Total Parking Revenue by Event</h4>
<form name="form2" action="EventAdmin.php" method="POST">
    <label for="eName">Event ID</label><br>
	<input type="text" name="eID" id="eID"><br>
    <label for="sDate">Start Date</label><br>
	<input type="DATE" name="sDate" id="sDate"><br>
    <label for="eDate">End Date</label><br>
	<input type="DATE" name="eDate" id="eDate"><br>
	<input type="submit" name="Submit5" class="button" value="Submit">
</form>
<?php
    if(isset($revenueByEventFlag)) {
        Echo "Total expected revenue is $sum";
    }

?>
<br><br><h4>Find Total Parking Revenue by Garage</h4>
<form name="form2" action="EventAdmin.php" method="POST">
    <label for="gName">Garage ID</label><br>
	<input type="text" name="gID" id="gID"><br>
    <label for="sDate">Start Date</label><br>
	<input type="DATE" name="sDate" id="sDate"><br>
    <label for="eDate">End Date</label><br>
	<input type="DATE" name="eDate" id="eDate"><br>
	<input type="submit" name="Submit6" class="button" value="Submit">
</form>
<?php
    if(isset($revenueByGarageFlag)) {
        Echo "Total expected revenue is $sum";
    }

?>
</body>
</html>