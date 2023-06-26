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
  background-color: #00a5ff;
  border: none;
  border-color: transparent;
  border-radius: 20px;
  box-shadow: 0 1px #999;
  opacity: 1;
}

input[type=submit]:hover {
	opacity: 0.7;
}

label {
	color: green;
}

</style>
</head>
<body>

<?php

$Date = MyGetDate();
$IRS = $SSN = $fname = $lname = $PostalCode = $city = "";
$street = $number = null;
$ready = false;

// Parse User Input 

if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	$ready = true;
	
	if(!empty($_POST["irs"])) {
		$IRS = test_input($_POST["irs"]);	
	}
	
	if(!empty($_POST["ssn"])) {
		$SSN = test_input($_POST["ssn"]);	
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

?>
<br>
<h1><div align = "center">Register</div></h1><br>
<br>

<div align="center">
<div class="solid"><br><br>
<form method="post" action="<?php echo 
htmlspecialchars($_SERVER["PHP_SELF"]);?>">

	Customer IRS Number *<br>
	<input type = "text" name = "irs" required> 
	<br><br>
	Customer Social Security Number * <br>
	<input type = "text" name = "ssn" required> 
	<br><br>
	First Name *<br>
	<input type = "text" name = "first_name" required> 
	<br><br>
	Last Name *<br>
	<input type = "text" name = "last_name" required> 
	<br><br>
	Street<br>
	<input type = "text" name = "street">
	<br><br>
	Number<br>
	<input type = "text" name = "number">
	<br><br>
	Postal Code *<br>
	<input type = "text" name = "postal_code" required> 
	<br><br>
	City *<br>
	<input type = "text" name = "city" required> 
	<br><br>
	
</div></div><br><br>

	<div align = "center">
	<input type = "submit" value = "Register"> 
	</div>
	<br><br>
</form>

<?php

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

	
if($ready) {
	
	// Execute Insert Query
	$query = "INSERT INTO customer VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("sssssiiss", $IRS, $SSN, $fname, $lname, $street, $number, $PostalCode, $city, $Date);
	if(!$stmt->execute()) {
		echo "Something went wrong. Try again more carefully";
	}
	else {
		echo "<label>You signed up successfully<label> <br>";
		$continue = true;
	}
}

// Return to Home Page
//if($continue) {
//	header("Location: /Manage.php");
//	exit;
//}
?> 



</body>
</html>