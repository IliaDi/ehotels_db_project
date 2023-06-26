<!DOCTYPE HTML>
<html>
<head>
<font face="serif" color="gray">
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

session_start();

$hotel_id = $hotel_group_id = $stars = $number_of_rooms = $PostalCode = $city = $email = $phone = "";
$street = $number = null;
$find = $update = false;

// Parse User Input 

if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	switch ($_POST["action"]) {
		case "find": 
				$find = true;
				if(!empty($_POST["hotel_id"])) {
					$hotel_id = test_input($_POST["hotel_id"]);	
				}
	
				if(!empty($_POST["hotel_group_id"])) {
					$hotel_group_id = ($_POST["hotel_group_id"]);	
				}
			break;
		
		case "update":
				$update = true;
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
				
			break;
	}
}
	
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
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

// Execute Find Query

if($find) {
	
	$_SESSION["hotel_id"] = $hotel_id;
	$_SESSION["hotel_group_id"] = $hotel_group_id;
	$query = "SELECT * FROM hotel WHERE hotel_id = '" . $hotel_id . "' AND hotel_group_id = '" .$hotel_group_id . "'"; 
	if( !($result = $conn->query($query)) ) {
			die("DataBase is temporarily unavailable. Query Failed: (" . $conn->errno . ") " . $conn->error);
		}

	if( $result->num_rows > 0 ) {
		$row = $result->fetch_assoc(); 
		$stars = $row["stars"]; 
		$number_of_rooms = $row["number_of_rooms"]; 
		$street = $row["street"];
		$number = $row["number"];
		$PostalCode = $row["postal_code"];
		$city = $row["city"];		
	}
	else {
		die( "No Hotel found. <br>");
	}
	
	$query = "SELECT * FROM hotel_email WHERE hotel_id = '" . $hotel_id . "'";
	if( !($result = $conn->query($query)) ) {
			die("DataBase is temporarily unavailable. Query Failed: (" . $conn->errno . ") " . $conn->error);
		}
	
	if( $result->num_rows > 0 ) {
		$row = $result->fetch_assoc();
		$email = $row["email"]; }
		
	$query = "SELECT * FROM hotel_phone WHERE hotel_id = '" . $hotel_id . "'";
	if( !($result = $conn->query($query)) ) {
			die("DataBase is temporarily unavailable. Query Failed: (" . $conn->errno . ") " . $conn->error);
		}
	
	if( $result->num_rows > 0 ) {
		$row = $result->fetch_assoc();
		$phone = $row["phone"]; 
	}

	
	$find = false;
}


?>

<h1><div align = "center">Hotel Information</div></h1><br>
<br>
<br>
<div align = "center">
<div class="solid">
<br>
<form method="post" action="<?php echo 
htmlspecialchars($_SERVER["PHP_SELF"]);?>">

	Hotel ID <br>
	<?php 
	$HotelQuery = "SELECT DISTINCT hotel_id FROM hotel";
	if( !($result = $conn->query($HotelQuery)) ) {
			die("DataBase is temporarily unavailable. Query Failed: (" . $conn->errno . ") " . $conn->error);
		}

	if( $result->num_rows > 0 ) {
		echo "<select name = \"hotel_id\">";
		echo "<option value = \"-\"></option>";
		while($row = $result->fetch_assoc()) {
			echo "<option>" . $row["hotel_id"] . "</option>";
		}
		echo "</select><br><br>";
	}
	?>	
	
	Hotel Group ID <br>
	<?php 
	$HotelGroupQuery = "SELECT hotel_group_id FROM hotel_group";
	if( !($result = $conn->query($HotelGroupQuery)) ) {
			die("DataBase is temporarily unavailable. Query Failed: (" . $conn->errno . ") " . $conn->error);
		}

	if( $result->num_rows > 0 ) {
		echo "<select name = \"hotel_group_id\">";
		echo "<option value = \"-\"></option>";
		while($row = $result->fetch_assoc()) {
			echo "<option>" . $row["hotel_group_id"] . "</option>";
		}
		echo "</select><br><br>";
	}
	?>
	
	<br><br></div><br><br>
	<input type="hidden" name="action" value="find">
	<input type="submit" value="Find Hotel"><br><br><br></div>
	
</form>

<div align = "center">
<div class="solid">
<br>
<form method="post" action="<?php echo 
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	
	Stars <br>
	<input type="text" name="stars" value="<?php echo $stars;?>">
	<br><br>
	Number of Rooms <br> 
	<input type="text" name="number_of_rooms" value="<?php echo $number_of_rooms;?>">
	<br><br>
	Street <br>
	<input type="text" name="street" value="<?php echo $street;?>">
	<br><br>
	Number <br>
	<input type="text" name="number" value="<?php echo $number;?>">
	<br><br>
	Postal Code <br>
	<input type="text" name="postal_code" value="<?php echo $PostalCode;?>">
	<br><br>
	City <br>
	<input type="text" name="city" value="<?php echo $city;?>">
	<br><br>
	Email  <br>
	<input type="text" name="hotel_email" value="<?php echo $email;?>">
	<br><br>
	Phone Number  <br>
	<input type="text" name="hotel_phone" value="<?php echo $phone;?>">
	<br><br></div></div><br>
	<div align = "center">
	<input type="hidden" name="action" value="update">
	<input type = "submit" value = "Update">
	</div>
	<br><br>


<?php

if($update) {
	$query = "UPDATE hotel 
			  SET stars = (?), 
				  number_of_rooms = (?), 
				  street = (?), 
				  number = (?), 
				  postal_code = (?),
				  city = (?)
			  WHERE hotel_id = (?) AND hotel_group_id = (?) ";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("sisiisss", $stars, $number_of_rooms, $street, $number, $PostalCode, $city, $_SESSION["hotel_id"], $_SESSION["hotel_group_id"]);
	if(!$stmt->execute()) {
		echo "Something went wrong. Try again more carefully";
	}
	else {
		echo "Updated succefully <br>";
	}
	
	$update = false;
}


?> 



</body>
</html>