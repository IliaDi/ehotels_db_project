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

$hotel_id = $hotel_group_id = $capacity = $view = $expandable = $repair = $price = "";
$room_id = null;
$find = $update = false;

// Parse User Input 

if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	switch ($_POST["action"]) {
		case "find": 
				$find = true;
				if(!empty($_POST["room_id"])) {
					$room_id = test_input($_POST["room_id"]);	
				}
	
				if(!empty($_POST["hotel_id"])) {
					$hotel_id = test_input($_POST["hotel_id"]);	
				}
	
				if(!empty($_POST["hotel_group_id"])) {
					$hotel_group_id = test_input($_POST["hotel_group_id"]);	
				}
			break;
		
		case "update":
				$update = true;
				if(!empty($_POST["capacity"])) {
					$capacity = test_input($_POST["capacity"]);	
				}
	
				if(!empty($_POST["view"])) {
					$view = test_input($_POST["view"]);	
				}
				
				if(!empty($_POST["expandable"])) { 
					$expandable = test_input($_POST["expandable"]);
				}
				
				if(!empty($_POST["repairs_need"])) {
					$repair = test_input($_POST["repairs_need"]);	
				}
				
				if(!empty($_POST["price"])) {
					$price = test_input($_POST["price"]);	
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
	
	$_SESSION["room_id"] = $room_id;
	$_SESSION["hotel_id"] = $hotel_id;
	$query = "SELECT * FROM hotel_room WHERE hotel_id = '" . $hotel_id . "' AND room_id = '" . $room_id . "'"; 
	if( !($result = $conn->query($query)) ) {
			die("DataBase is temporarily unavailable. Query Failed: (" . $conn->errno . ") " . $conn->error);
		}

	if( $result->num_rows > 0 ) {
		$row = $result->fetch_assoc(); 
		$capacity = $row["capacity"]; 
		$view = $row["view"]; 
		$expandable = $row["expandable"];
		$repair = $row["repair_need"];
		$price = $row["price"];	
	}
	else {
		echo "No Hotel Room found. <br>";
	}
	
	$find = false;
}


?>

<h1><div align = "center">Room Information</div></h1><br>
<br>
<br>
<div align = "center">
<div class="solid">
<br>
<form method="post" action="<?php echo 
htmlspecialchars($_SERVER["PHP_SELF"]);?>">

	Enter Room ID <br>
	<input type = "text" name = "room_id" value = "<?php echo $room_id;?>" required>
	<br><br>
	Enter Hotel ID <br>
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
	
	</div><br><br>
	<input type="hidden" name="action" value="find">
	<input type="submit" value="Find Hotel"><br><br><br></div>
	
</form>

<div align = "center">
<div class="solid">
<br>
<form method="post" action="<?php echo 
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	
	Room Capacity <br>
	<select name = "capacity">
		<option value = "-"></option>
		<option value = "1" <?php if($capacity == 1) echo "selected"; ?>> 1 </option>
		<option value = "2" <?php if($capacity == 2) echo "selected"; ?>> 2 </option>
		<option value = "3" <?php if($capacity == 3) echo "selected"; ?>> 3 </option>
		<option value = "4" <?php if($capacity == 4) echo "selected"; ?>> 4</option>
	</select>
	<br><br>
	View <br>
	<select name="view">
		<option value = "-"></option>		
		<option value="sea view" <?php if($view == "sea view") echo "selected"; ?>> Sea View </option>
		<option value="street view" <?php if($view == "street view") echo "selected"; ?>> Street View </option>
		<option value="garden view" <?php if($view == "garden view") echo "selected"; ?>> Garden View </option>
	</select>
	<br><br>
	Expandable <br>
	<select name="expandable">
		<option value = "-"></option>	
		<option value="yes ,extra beds" <?php if($expandable == "yes ,extra beds") echo "selected"; ?>> Extra Beds </option>
		<option value="yes, connects with next room" <?php if($expandable == "yes, connects with next room") echo "selected"; ?>> Connects with Next Room </option>
		<option value="no" <?php if($expandable == "no") echo "selected"; ?>> No </option>
	</select>
	<br><br>
	Needs Repairs <br>
	<select name="repairs_need">
		<option value = "-"></option>
		<option value="yes" <?php if($repair == 'yes') echo "selected"; ?>>Yes</option>
		<option value="no" <?php if($repair == 'no') echo "selected"; ?>>No</option>
	</select>
	<br><br>
	Price <br>
	<input type="text" name="price" value="<?php echo $price;?>" required>
	<br><br>
	</div></div><br>
	<div align = "center">
	<input type="hidden" name="action" value="update">
	<input type = "submit" value = "Update">
	</div>
	<br><br>


<?php

if($update) {
	$query = "UPDATE hotel_room 
			  SET capacity = (?), 
				  view = (?), 
				  expandable = (?), 
				  repair_need = (?), 
				  price = (?)
			  WHERE hotel_id = (?) AND room_id = (?) ";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("ssssisi", $capacity, $view, $expandable, $repair, $price, $_SESSION["hotel_id"], $_SESSION["room_id"]);
	if(!$stmt->execute()) {
		echo "Something went wrong. Try again more carefully";
	}
	else {
		echo "Updated succefully <br>";
	}
	
	$update = false;
}


?> 

</font>

</body>
</html>