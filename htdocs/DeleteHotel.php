<!DOCTYPE HTML>
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>

<?php

$hotel_id = $hotel_group_id = "";
$ready = false;

if($_SERVER["REQUEST_METHOD"] == "POST") {
	
		$ready = true;
		if(!empty($_POST["hotel_id"])) {
					$hotel_id = test_input($_POST["hotel_id"]);	
				}
	
		if(!empty($_POST["hotel_group_id"])) {
					$hotel_group_id = test_input($_POST["hotel_group_id"]);	
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

// Execute Delete Query

if($ready) {
	
		$query = "DELETE FROM hotel WHERE hotel_id = '" . $hotel_id . "' AND hotel_group_id = '" . $hotel_group_id . "'";
		if( !($result = $conn->query($query)) ) {
				die("DataBase is temporarily unavailable. Query Failed: (" . $conn->errno . ") " . $conn->error);
			}
		else {
			if($conn->affected_rows > 0)
				echo "Delete was successful! <br>";
			else
				echo "Hotel does not exists! <br>";
		}
		
}

?>


<h1><div align = "center">Delete a Hotel</div></h1><br>
<br>
<br>

<form method="post" action="<?php echo 
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	
	 Enter Hotel ID: 
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
	
	Enter Hotel Group ID:
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
	
	<input type = "submit" value = "Delete Hotel"><br><br>

	
</form>
</body>
</html>