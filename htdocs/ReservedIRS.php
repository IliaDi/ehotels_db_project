<!DOCTYPE HTML>
<html>
<body>
<font face="serif" color="gray">
<style>

hr {
    display: block;
    height: 1px;
    border: 0;
    border-top: 1px solid #ccc;
    margin: 1em 0;
    padding: 0;
	width: 25%
}
</style>


<?php 

session_start();
$query = $payment = $IRS = "";
$employee_IRS = "123456789";

if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	$ready = true;
	$AvQuery =" SELECT room_id 
				FROM reserves as r
				WHERE ( (r.start_date < (?) AND (?) < r.finish_date) OR (r.start_date < (?) AND (?) < r.finish_date) ) AND room_id = (?)";
	
	$pQuery = " SELECT customer_irs_number
				FROM customer
				WHERE customer_irs_number = (?) ";

	if(isset($_POST["IRS"])) {
		$IRS = test_input($_POST["IRS"]);		
	}
	
	if(isset($_POST["InDate"])) {
		$InDate = test_input($_POST["InDate"]);		
	}

	if(isset($_POST["OutDate"])) {
		$OutDate = test_input($_POST["OutDate"]);
	}

	if(isset($_POST["payment"])) {
		$payment = test_input($_POST["payment"]);
	}	
}


function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function MyGetDate() {
	$arrDate = getdate();
	$Year = $arrDate["year"];
	$Month = ($arrDate["mon"] < 10) ? ( "0" . $arrDate["mon"] ) : $arrDate["mon"] ;
	$Day = ($arrDate["mday"] < 10) ? ("0" . $arrDate["mday"]) : $arrDate["mday"] ;
	$Date = ($Year . "-" . $Month . "-" . $Day);
	return $Date;
}

function CountDays($InDate , $OutDate) {
	$inDate = new DateTime($InDate);
	$outDate = new DateTime($OutDate);
	$days = $outDate->diff($inDate)->format("%a");
	return $days;
}

// Connect to DataBase
$servername = 'localhost';
$username = 'root';
$password = '1q2w3e4r5t6y';
$dbname = 'ehotels';

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

if($ready) {
	$stmt = $conn->prepare($AvQuery);
	$stmt->bind_param("ssssi", $OutDate, $OutDate, $InDate, $InDate, $_SESSION["room_id"]);
	if(!$stmt->execute()) {
		echo $stmt->error . "<br>";
	}
	$result = $stmt->get_result();
	if($result->num_rows > 0)
			die("Room is no longer available");
	
	$days = CountDays($InDate, $OutDate);
	
	
	$stmt = $conn->prepare($pQuery);
	$stmt->bind_param("s", $IRS);
	if(!$stmt->execute()) {
		echo $stmt->error . "<br>";
	}
	$result = $stmt->get_result();
	
	if($result->num_rows == 0) {
			die("You are Not Registered.Please Sign Up");
	}
	
	if($payment == "card")
		$paid = "yes";
	else
		$paid = "no";
	
	$ResQuery = "INSERT INTO reserves VALUES (?, ?, ?, ?, ?, ?)";
	$stmt = $conn->prepare($ResQuery);
	$stmt->bind_param("ssisss", $_SESSION["hotel"], $IRS, $_SESSION["room_id"], $InDate, $OutDate, $paid);
	if(!$stmt->execute()) {
		echo $stmt->error . "<br>";
	}
	
	$RentQuery = "INSERT INTO rents VALUES (?, ?, ?, ?, ?, ?)";
	$stmt = $conn->prepare($RentQuery);
	$stmt->bind_param("ssisss", $employee_IRS, $IRS, $_SESSION["room_id"], $_SESSION["hotel"], $InDate, $OutDate);
	if(!$stmt->execute()) {
		echo $stmt->error . "<br>";
	}
	
	$payment_amount = $_SESSION["price"] * $days;
	$TransQuery = "INSERT INTO payment_transaction VALUES (?, ?, ?, ?, ?, ?)";
	$stmt = $conn->prepare($TransQuery);
	$stmt->bind_param("ssisis", $employee_IRS, $IRS, $_SESSION["room_id"], $_SESSION["hotel"], $payment_amount, $payment);
	if(!$stmt->execute()) {
		echo $stmt->error . "<br>";
	}
}

?>

<div align="center">
<h1>Your Reservation</h1>
<br><br>
Your IRS Number: <br>
<?php echo $IRS; ?><br><br>
<hr>
Hotel: <br>
<?php echo $_SESSION["hotel"];?><br><br>
Room Capacity: <br>
<?php echo $_SESSION["capacity"];?><br><br>
Check-In Date: <br>
<?php echo $InDate;?><br><br>
Check-Out Date: <br>
<?php echo $OutDate;?><br><br>
Total Payment Amount: <br>
<?php echo $payment_amount; ?> € <br><br>
<div>

 

</body>
</html>