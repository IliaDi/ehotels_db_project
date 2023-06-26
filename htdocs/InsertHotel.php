<!DOCTYPE HTML>
<html>
<head>
<style>
.error {color: #FF0000;}

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

select {
    padding: 5px 5px;
    border: 1px solid #f1f1f1;
    border-radius: 4px;
    background-color: #fff;
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
</head>
<body>

<?php

$hotel_id = $hotel_group_id = $stars = $number_of_rooms = $PostalCode = $city = $email = $phone = "";
$street = $number = null;
$ready = false;

// Parse User Input 

if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	$ready = true;
	
	if(!empty($_POST["hotel_id"])) {
		$hotel_id = test_input($_POST["hotel_id"]);	
	}
	
	if(!empty($_POST["hotel_group_id"])) {
		$hotel_group_id = test_input($_POST["hotel_group_id"]);	
	}
	
	if(!empty($_POST["stars"])) {
		$stars = test_input($_POST["stars"]);	
	}
	
	if(!empty($_POST["number_of_rooms"])) {
		$number_of_rooms = test_input($_POST["number_of_rooms"]);	
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
	
	if(!empty($_POST["hotel_email"])) {
		$email = test_input($_POST["hotel_email"]);	
	}
	
	if(!empty($_POST["hotel_phone"])) {
		$phone = test_input($_POST["hotel_phone"]);	
	}
}
	
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>
<br>
<h1><div align = "center">Insert new Hotel</div></h1><br>
<br>
<br>
<div align = "center">
<div class="solid"><br>
<form method="post" action="<?php echo 
htmlspecialchars($_SERVER["PHP_SELF"]);?>">

	Hotel Name * <br>
	<input type = "text" name = "hotel_id" required><br><br>
	Hotel Group <br>
	<select name = "hotel_group_id">
		<option value = "-"></option>
		<option value = "Paradise city international">Paradise City International</option>
		<option value = "Friends and family">Friends and Family</option>
		<option value = "Karjenners">Karjenners</option>
		<option value = "Kitsch hotels">Kitsch Hotels</option>
	<br><option value = "F&g hotels">F&G Hotels</option>
	</select>
	<br><br>
	Stars <br>
	<select name = "stars">
		<option value = "-"></option>
		<option value = "1">1</option>
		<option value = "2">2</option>
		<option value = "3">3</option>
		<option value = "4">4</option>
		<option value = "5">5</option>
	</select>
	<br><br>
	Number of Rooms * <br>
	<input type = "text" name = "number_of_rooms" required><br><br>
	Street * <br>
	<input type = "text" name = "street" required> 
	<br><br>
	Number * <br>
	<input type="text" name="number" required>
	<br><br>
	Postal Code * <br>
	<input type="text" name="postal_code" required>
	<br><br>
	City * <br>
	<input type="text" name="city" required><br><br>
	Email * <br>
	<input type="text" name="hotel_email" required>
	<br><br>
	Phone Number * <br>
	<input type="text" name="hotel_phone" required>
	<br><br>
	</div><br><br>
	<input type="submit" value="Insert Hotel">
	<br><br></
	<br><br>
</form>
	
	
<?php

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
$query = "INSERT INTO hotel VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssssiis", $hotel_id, $hotel_group_id, $stars, $number_of_rooms, $street, $number, $PostalCode, $city);
if(!$stmt->execute()) 
	die("Something went wrong. Try again more carefully");

$query = "INSERT INTO hotel_email VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $hotel_id, $email);
if(!$stmt->execute()) 
	die( "Something went wrong. Try again more carefully");

$query = "INSERT INTO hotel_phone VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $hotel_id, $phone);
if(!$stmt->execute()) 
	die( "Something went wrong. Try again more carefully");

echo "Inserted succesfully";
	
}

// Return to Home Page
//if($continue) {
//	header("Location: /Manage.php");
//	exit;
//}
?> 



</body>
</html>