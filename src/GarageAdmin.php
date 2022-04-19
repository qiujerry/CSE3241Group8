<html>
<body>
<?php 
	
	
	$garageName= NULL;
	$parkingNum= NULL;
	$maxNum = NULL;
	$con=mysqli_connect('127.0.0.1','aduser','phpwd','ParkingMaster'); //INSERT YOUR OWN PASSWORD TO RUN FILE
	
	//this is for setting max parking spaces in a given garage
	if(isset($_POST['Submit'])) {
		$flag = false;
		$garageName = $_POST['subject'];
		$parkingNum = $_POST['subject2'];
		if (!$con) {	
			die('Cannot connect'.mysqli_connect_error());
		}
		else {
			$maxNum = "select max(b.c) from
							(select count(*) as c from reservation, garage
							where reservation.garage_id = garage.garage_id
							and garage.name = \"$garageName\" and reservation.reservation_date > curDate()
							group by reservation.reservation_date) as b";
			//looks into the future to find the day with highest amount of reservations, assumes reducing max number of parking will be indefinite
			$result = mysqli_query($con,$maxNum) or die(mysqli_connect_error());
			$max2 = mysqli_fetch_array($result);
			if($parkingNum > $max2["max(b.c)"]){
				
				$sql2="update GARAGE set numSpaces=$parkingNum where GARAGE.name=\"$garageName\"";
				$result2=mysqli_query($con,$sql2) or die(mysqli_connect_error());
			} 
			else {
				$flag = true;
			}
		}
	}

	//This is for querying garage availability for a certain date
	$inputDate = NULL;
	$count = NULL;
	if(isset($_POST['Submit2'])) {
		if (!$con) {	
			die('Cannot connect'.mysqli_connect_error());
		}
		else {
			$inputDate=$_POST['date'];
			$garageName=$_POST['gName'];
			$sql = "select count(*) as c
				from reservation, event, garage
				where reservation.event_id = event.event_id and
				reservation.garage_id = garage.garage_id and
				garage.name = \"$garageName\" and
				reservation.status = \"active\" and
				event.edate = \"$inputDate\"";
			$result = mysqli_query($con,$sql) or die(mysqli_connect_error());
			$var = mysqli_fetch_array($result);
			$count = $var["c"];
			
			$sql = "select numSpaces from GARAGE where GARAGE.name = \"$garageName\"";
			$result = mysqli_query($con,$sql) or die(mysqli_connect_error());
			$max1 = mysqli_fetch_array($result);
			$max = $max1['numSpaces'];
			$percent = ($count / $max) * 100;
		}
	}

	//this is for adding a new garage
	if(isset($_POST['Submit3'])) {
		if (!$con) {	
			die('Cannot connect'.mysqli_connect_error());
		}
		else {
			$garageName=$_POST['gName'];
			$garage_ID = $_POST['gID'];
			$address = $_POST['address'];
			$numSpaces = $_POST['numSpaces'];
			$sql = "INSERT INTO GARAGE (garage_ID, name, address, numSpaces) VALUES (\"$garage_ID\", \"$garageName\", \"$address\", \"$numSpaces\")";
			$result = mysqli_query($con,$sql) or die(mysqli_connect_error());
		}
	}
?>

<h3>What would you like to do, Garage Administrator?</h3>
<h4>Change Max Parking in a Garage</h4>

<form name="form" action="GarageAdmin.php" method="POST">
	<label for="subject">Garage Name</label><br>
	<input type="text" name="subject" id="subject"><br>
	<label for="subject2">Number of Available Spaces</label><br>
	<input type="number" name="subject2" id="subject2"><br>
	<input type="submit" name="Submit" class="button" value="Submit">
	</form>
<?php 
	if(isset($maxNum) && $maxNum != NULL && isset($flag) && $flag == false) {
		echo $garageName . " max parking has been set to " . $parkingNum;
	}
	if(isset($flag) && $flag == true) {
		Echo "ERROR: There are more spaces currently reserved than what you wanted to change the capacity of the garage to";
	}
?>
<br><br><h4>Find Capacity of a Garage on a Certain Date</h4>
<form name="form2" action="GarageAdmin.php" method="POST">
	<label for="gName">Garage Name</label><br>
	<input type="text" name="gName" id="gName"><br>
	<label for="date">Select Date</label><br>
	<input type="DATE" name="date" id="date"><br>
	<input type="submit" name="Submit2" class="button" value="Submit">
</form>
<?php 
	if(isset($count) && $count != NULL && isset($max) && isset($percent)) {
		echo $garageName . " has " . $count . " spaces reserved out of its " . $max . " number of spaces on " . $inputDate . ". It is " . $percent . "% full.";
	}
?>

<br><br><h4>Add a New Garage</h4>
<form name="form2" action="GarageAdmin.php" method="POST">
	<label for="gName">Garage Name</label><br>
	<input type="text" name="gName" id="gName"><br>
	<label for="gID">Garage ID</label><br>
	<input type="int" name="gID" id="gID"><br>
	<label for="address">Address</label><br>
	<input type="string" name="address" id="address"><br> 
	<label for="numSpaces">Number of Parking Spaces</label><br>
	<input type="int" name="numSpaces" id="numSpaces"><br>
	<input type="submit" name="Submit3" class="button" value="Submit">
</form>


</body>
</html>
