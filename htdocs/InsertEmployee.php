<!DOCTYPE HTML>
<html>
<head>
<font face="serif">

<style>
.error {color: #FF0000;}

.solid {
	border: 1px solid gray;
	border-radius: 4px; 
	width: 37% }
	
hr {
    display: block;
    height: 1px;
    border: 0;
    border-top: 1px solid #ccc;
    margin: 1em 0;
    padding: 0;
	width: 15%
}

	
input[type=text] {
    width: 50%;
    padding: 5px 10px;
    margin: 8px 0;
    box-sizing: border-box;
    border: 1px solid #f1f1f1;
    border-radius: 2px;
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
</head>
<body>

<?php

$IRS = $SSN = $fname = $lname = $PostalCode = $city = $hotel = "";
$street = $number = $FinishDate = null;
$StartDate = MyGetDate();
$ready = false;
$good = true;

// Parse User Input 

if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	$ready = true;
	
	if(!empty($_POST["irs"])) {
		$IRS = test_input($_POST["irs"]);
		if(!(ctype_digit($IRS) AND isset($IRS[8]) AND !isset($IRS[9]))) 
			$good = false;
	}
	
	if(!empty($_POST["ssn"])) {
		$SSN = test_input($_POST["ssn"]);
		if(!(ctype_digit($IRS) AND isset($SSN[10]) AND !isset($SSN[11])))
			$good = false;
	}
	
	if(!empty($_POST["first_name"])) {
		$fname = test_input($_POST["first_name"]);	
	}
	
	if(!empty($_POST["last_name"])) {
		$lname = test_input($_POST["last_name"]);	
	}
	
	if(!empty($_POST["street"])) 
		$street = test_input($_POST["street"]);
	
	if(!empty($_POST["number"])) 
		$number = test_input($_POST["number"]);
	
	if(!empty($_POST["postal_code"])) {
		$PostalCode = test_input($_POST["postal_code"]);	
	}
	
	if(!empty($_POST["city"])) {
		$city = test_input($_POST["city"]);	
	}
	
	if(!empty($_POST["hotel_id"])) {
		$hotel = test_input($_POST["hotel_id"]);		
	}
	
	if(!empty($_POST["position"])) {
		$position = test_input($_POST["position"]);		
	}
	
	if(!empty($_POST["FinishDate"])) {
		$FinishDate = test_input($_POST["FinishDate"]);		
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


$continue = false;

// Connect to DataBase
$servername = 'localhost';
$username = 'root';
$password = '1q2w3e4r5t6y';
$dbname = 'ehotels';

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}


?>

<h1><div align = "center"><br>Hire An Employee</div></h1><br>
<br>
<br>
<div align = "center">
<div class="solid"><br><br>
<form method="post" action="<?php echo 
htmlspecialchars($_SERVER["PHP_SELF"]);?>">

	Employee IRS Number * <br>
	<input type = "text" name = "irs" required> 
	<br><br>
	Employee Social Security Number * <br>
	<input type = "text" name = "ssn" required> 
	<br><br>
	First Name * <br>
	<input type = "text" name = "first_name" required> 
	<br><br>
	Last Name * <br>
	<input type = "text" name = "last_name" required> 
	<br><br>
	Street <br>
	<input type = "text" name = "street">
	<br><br>
	Number <br>
	<input type = "text" name = "number">
	<br><br>
	Postal Code * <br>
	<input type = "text" name = "postal_code" required> 
	<br><br>
	City * <br>
	<input type = "text" name = "city" required> 
	<br><br><hr>
	Works for <br>
	<?php 
	$HotelQuery = "SELECT DISTINCT hotel_id FROM hotel";
	if( !($result = $conn->query($HotelQuery)) ) {
			die("DataBase is temporarily unavailable. Query Failed: (" . $conn->errno . ") " . $conn->error);
		}

	if( $result->num_rows > 0 ) {
		echo "<select name = \"hotel_id\">";
		echo "<option value=\"-\"></option>";
		while($row = $result->fetch_assoc()) {
			echo "<option>" . $row["hotel_id"] . "</option>";
		}
		echo "</select><br><br>";
	}
	?>	
	Working Position * <br>
	<input type = "text" name = "position" required> 
	<br><br>
	Finish Date <br>
	<input type="date" name="FinishDate"/>
	<br><br><br></div></div><br><br>
	<div align = "center">
	<input type = "submit" value = "Hire"> 
	</div>
	<br><br>
</form>

<?php
	
if($ready AND $good) {
	
// Execute Insert Query
$query = "INSERT INTO employee VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssssiis", $IRS, $SSN, $fname, $lname, $street, $number, $PostalCode, $city);
if(!$stmt->execute()) {
	echo $stmt->error . "<br>";
	echo "Something went wrong. Try again more carefully";
}
	else {	
		$query = "INSERT INTO works VALUES (?, ?, ?, ?, ?)";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("sssss", $IRS, $hotel, $position, $StartDate, $FinishDate);
		if(!$stmt->execute()) {
			echo $stmt->error . "<br>";
			echo "Something went wrong. Try again more carefully";
		}
		else {
			echo "Your Query executed successfully <br>";
			$continue = true;
		}
	}
}

if($ready AND !$good)
	echo "You gave wrong inputs";

// Return to Home Page
//if($continue) {
//	header("Location: /Manage.php");
//	exit;
//}
?> 



</body>
</html>