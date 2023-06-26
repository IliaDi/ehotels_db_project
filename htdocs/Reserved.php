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

input[type=submit] {
  display: inline-block;
  padding: 7px 15px;
  font-size: 14px;
  cursor: pointer;
  text-align: center;
  text-decoration: none;
  outline: none;
  color: #fff;
  background-color: #00a5ff;
  border: none;
  border-color: transparent;
  border-radius: 10px;
  box-shadow: 0 1px #999;
  opacity: 1;
}

input[type=submit]:hover {
	opacity: 0.7;
}

</style>


<?php 

session_start();
$query = $payment = "";
$employee_IRS = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	$ready = true;
	$AvQuery =" SELECT room_id 
				FROM reserves as r
				WHERE ((r.start_date < (?) AND (?) < r.finish_date) OR (r.start_date < (?) AND (?) < r.finish_date) OR ((?) < r.start_date AND r.finish_date < (?)) AND room_id = (?))";
	
	$pQuery = " SELECT customer_irs_number
				FROM customer
				WHERE first_name = (?) AND last_name = (?) ";
	
	if(isset($_POST["fname"])) {
		$fname = test_input($_POST["fname"]);		
	}
	
	if(isset($_POST["lname"])) {
		$lname = test_input($_POST["lname"]);		
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

$Date = MyGetDate();
if( $Date > $InDate ) 
		die("Are you crazy? You can't go back in time!");
		
if( $InDate >= $OutDate ) 
		die("Check-Out Date can't be before Check-In Date");
		
if($ready) {
	$stmt = $conn->prepare($AvQuery);
	$stmt->bind_param("ssssssi", $OutDate, $OutDate, $InDate, $InDate, $InDate, $OutDate, $_SESSION["room_id"]);
	if(!$stmt->execute()) {
		echo $stmt->error . "<br>";
	}
	$result = $stmt->get_result();
	if($result->num_rows > 0)
			die("Room is no longer available. Return to <a href=\"/home.html\" target=\"_blank\" style=\"color:dodgerblue\"> Home Page</a>.");
	
	$days = CountDays($InDate, $OutDate);
		
	$stmt = $conn->prepare($pQuery);
	$stmt->bind_param("ss", $fname, $lname);
	if(!$stmt->execute()) {
		echo $stmt->error . "<br>";
	}
	$result = $stmt->get_result();
	if($result->num_rows > 1) {
 			header("Location: /ReserveIRS.php");
			exit; }
			
	if($result->num_rows == 0) {
			die("You are Not Registered. Please <a href=\"/SignUp.php\" target=\"_blank\" style=\"color:dodgerblue\"> Sign Up </a>.");
	}
	

	$row = $result->fetch_assoc();
	$IRS = $row["customer_irs_number"];
	
	$eQuery = " SELECT employee_irs_number
				FROM works
				WHERE hotel_id = '" . $_SESSION["hotel"] . "'";
	
	if( !($result = $conn->query($eQuery)) ) {
		die("Query Failed: (" . $conn->errno . ") " . $conn->error);
	}
	
	if($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$employee_IRS = $row["employee_irs_number"];
	}
	
	else 
		die("We are sorry. No employee is on duty now. Try again in few hours.");
	
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
<?php echo $payment_amount; ?> € <br><br><br><br><br>

<form method="post" action="/home.html">
<input type="submit" value="Home Page">
</form>
<div>

 

</body>
</html>