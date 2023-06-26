<!DOCTYPE HTML>
<html>
<head>
<font face = "serif">
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

$hotel_id = $capacity = $view = $expandable = $repair = $price = "";
$room_id = null;
$ready = false;

// Parse User Input 

if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	$ready = true;
	
	if(!empty($_POST["hotel_id"])) {
		$hotel_id = test_input($_POST["hotel_id"]);	
	}
	
	if(!empty($_POST["capacity"])) {
		$capacity = test_input($_POST["capacity"]);	
	}

	if(!empty($_POST["expandable"])) {
		$expandable = test_input($_POST["expandable"]);	
	}
	
	if(!empty($_POST["view"])) {
		$view = test_input($_POST["view"]);	
	}
	
	if(!empty($_POST["repairs_need"])) 
		$repair = test_input($_POST["repairs_need"]);
	
	if(!empty($_POST["price"])) 
		$price = test_input($_POST["price"]);
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


?>

<br>
<h1><div align = "center">Insert a new Room</div></h1><br>
<br>
<br>
<div align = "center">
<div class="solid"><br>
<form method="post" action="<?php echo 
htmlspecialchars($_SERVER["PHP_SELF"]);?>">

	Hotel Name <br>
	
	<?php 
	$HotelQuery = "SELECT DISTINCT hotel_id FROM hotel";
	if( !($result = $conn->query($HotelQuery)) ) {
			die("DataBase is temporarily unavailable. Request Failed: (" . $conn->errno . ") " . $conn->error);
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
	
	Room Capacity <br>
	<select name = "capacity">
		<option value = "-"></option>
		<option value = "1">1</option>
		<option value = "2">2</option>
		<option value = "3">3</option>
		<option value = "4">4</option>
	</select>
	<br><br>
	View <br>
	<select name="view">
		<option value = "-"></option>		
		<option value="sea view">Sea View</option>
		<option value="street view">Street View</option>
		<option value="garden view">Garden View</option>
	</select>
	<br><br>
	Expandable <br>
	<select name="expandable">
		<option value = "-"></option>	
		<option value="yes ,extra beds">Extra Beds</option>
		<option value="yes, connects with next room">Connects with Next Room</option>
		<option value="no">No</option>
	</select>
	<br><br>
	Needs Repairs <br>
	<select name="repairs_need">
		<option value = "-"></option>
		<option value="yes">Yes</option>
		<option value="no">No</option>
	</select>
	<br><br>
	Price  <br>
	<input type="text" name="price" required>
	<br><br>
	</div><br><br>
	<input type="submit" value="Insert Room">
	</div>
	<br><br>
	<br><br><br>

</form>
	
	
<?php
	
if($ready) {
	
// Execute Insert Query
$query = "INSERT INTO hotel_room VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("isisssi", $room_id, $hotel_id, $capacity, $view, $expandable, $repair, $price);
if(!$stmt->execute()) {
	echo "Something went wrong. Try again more carefully. All fields required. ";
}
else {
	echo "Inserted successfully <br>"; 
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