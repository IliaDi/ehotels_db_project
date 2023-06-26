<!DOCTYPE HTML>
<html>
<head>
<style> 
input[type=text] {
    width: 8%;
    padding: 7px 5px;
    margin: 8px 0;
    box-sizing: border-box;
    border: 1px solid #00a5ff;
    border-radius: 2px;
}

select {
    padding: 5px 5px;
    border: none;
    border-radius: 4px;
    background-color: #f1f1f1;
}

.solid {
	border: 2px solid gray;
	border-radius: 4px; 
	width: 37% }


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



</style>
</head>
<body>
<font face="serif" color="gray">

<?php

// Parse User Input 

$query = "";
		  
$city = $hotel_group_id = $InDate = $OutDate = $stars = $capacity = $lprice = $rprice = ""; 

if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	$query = "SELECT hotel.hotel_group_id, hotel.hotel_id, hotel.stars, hotel.city, hr.room_id, hr.price, hr.capacity, hr.view, COUNT(*) as num_rooms
		  FROM hotel, hotel_room as hr
		  WHERE hr.hotel_id = hotel.hotel_id ";

		  
	$Date = MyGetDate();

	if(isset($_POST["city"])) {
		$city = test_input($_POST["city"]);
		if($city != '-' ) {
			$query .= " AND city = '" . $city . "'";
		}
	}
	
	if(isset($_POST["hotel_group_id"])) {
		$hotel_group_id = ($_POST["hotel_group_id"]);
		if($hotel_group_id != '-' ) {
			$query .= " AND hotel_group_id = '" . $hotel_group_id . "'";
		}
	}
	
	if(isset($_POST["stars"])) {
		$stars = test_input($_POST["stars"]);
		if($stars != '-') {
			$query .= " AND stars = '" . $stars . "'";
		}
	}
	
	if(isset($_POST["capacity"])) {
		$capacity = test_input($_POST["capacity"]);
		if($capacity != '-') {
			$query .= " AND hr.capacity = '" . $capacity . "'";
		}
	}
	
	if(isset($_POST["lprice"])) {
		$lprice = test_input($_POST["lprice"]);
		if($lprice != null) {
			$query .= " AND price > " . $lprice ;
		}
	}

	if(isset($_POST["rprice"])) {
		$rprice = test_input($_POST["rprice"]);
		if($rprice != null) {
			$query .= " AND price < " . $rprice ;
		}
	}
	
	if(isset($_POST["InDate"])) {
		$InDate = test_input($_POST["InDate"]);
		if($InDate != null) {
			$AuxQuery = " AND hr.room_id NOT IN 
						( SELECT r.room_id 
						  FROM reserves as r
						  WHERE ";
			$query .=  $AuxQuery . " ( r.start_date < '" . $InDate . "' ) AND (r.finish_date > '" . $InDate . "'))";
		}
	}

	if(isset($_POST["OutDate"])) {
		$OutDate = test_input($_POST["OutDate"]);
		if($OutDate != null) {
			$AuxQuery = " AND hr.room_id NOT IN 
						( SELECT r.room_id 
						  FROM reserves as r
						  WHERE ";
			$query .= $AuxQuery . " (r.start_date < '" . $OutDate . "') AND (r.finish_date > '" . $OutDate . "'))";
		}
	}
	
	if(isset($_POST["amenity"])) {
		$amenity = ($_POST["amenity"]);
		$AuxQuery =   " AND hr.room_id = 
					   	ANY
						(SELECT hr.room_id
						FROM hotel_room as hr JOIN amenities as a ON (hr.room_id = a.room_id)
						WHERE (a.amenity_names = '";
					  
		$N = count($amenity);
		for($i = 0; $i < $N; $i++) {
			$query .= $AuxQuery . $amenity[$i] . "'))";
		}
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

<h1><div align = "center">e - Hotel</div></h1><br>
<br>
<div align = "center">
<div class="solid">
<br>
<form method="post" action="<?php echo 
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	
	City <br>
	
	<?php 
	$CityQuery = "SELECT DISTINCT city FROM hotel ORDER BY city";
	if( !($result = $conn->query($CityQuery)) ) {
			die("DataBase is temporarily unavailable. Query Failed: (" . $conn->errno . ") " . $conn->error);
		}

	if( $result->num_rows > 0 ) {
		echo "<select name = \"city\">";
		echo "<option value = \"-\"></option>";
		while($row = $result->fetch_assoc()) {
			echo "<option>" . $row["city"] . "</option>";
		}
		echo "</select><br><br>";
	}
	?>
	
	Hotel Group <br>

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
	
	Stars:
	
	<select name="stars">
		<option value="-"></option>
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
	</select>
	<br>
	<br>
	
	Price Range (per Room per Day):  
	<input type="text" name="lprice">  
	<input type="text" name="rprice">
	<br><br>
	
	Check-in: 
	<input type="date" name="InDate"/>
	<br><br>
	Check-out:
	<input type="date" name="OutDate"/>
	<br><br><br>
	
	Amenities:<br>
	<input type="checkbox" name="amenity[]" value="kitchen"> Kitchen<br>
	<input type="checkbox" name="amenity[]" value="aircondition"> Aircondition<br>
	<input type="checkbox" name="amenity[]" value="wifi"> WiFi<br>
	<input type="checkbox" name="amenity[]" value="balcony"> Balcony<br>
	<input type="checkbox" name="amenity[]" value="washing machine"> Washing Machine<br>
	<input type="checkbox" name="amenity[]" value="free parking"> Free Parking<br>
	<input type="checkbox" name="amenity[]" value="breakfast"> Breakfast<br>
	<input type="checkbox" name="amenity[]" value="pool"> Swimming Pool<br>
	<br>
	<br>
	
	Room Capacity:
	
	<select name="capacity">
		<option value="-"></option>
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
	</select>
	<br>
	<br>
	</div></div>
	<br><br>
	<div align = "center">
	<input type="submit"  value="Check Availability">
	</div>
	</form>
	<br>
	<br>
	<br>
	<br>
	<br>
	<style>

table {
    font-family: sans-serif;
    border-collapse: collapse;
    width:80%;
}

td, th {
    border-bottom: 1px solid #dddddd;
    text-align: left;
    padding: 15px;

}

tr:hover {background-color: #f5f5f5;}

hr {
    display: block;
    height: 1px;
    border: 0;
    border-top: 1px solid #ccc;
    margin: 1em 0;
    padding: 0;
	width: 45%
}

</style>

<?php 

// Execute User Defined Query

if( $query != "") {
	
	if( $InDate == null) {
		$InDate = $Date;
	}
	if( $Date > $InDate ) {
		die("Are you crazy? You can't go back in time!");
	}	
	
	$query .= " GROUP BY hotel.hotel_group_id, hotel.hotel_id, hotel.stars, hotel.city, hr.price, hr.capacity, hr.view
				ORDER BY hotel.city, hr.price";

	if( !($result = $conn->query($query)) ) {
		die("Query Failed: (" . $conn->errno . ") " . $conn->error);
	}

	if( $result->num_rows > 0 ) {
		echo  " 
				<form method = \"post\" action = \" /Reserve.php \"> 
				<div align=\"center\">
				<hr>
				<table id=\"example\" class=\"display\">
				<tr>
				<th>Hotel Group</th>
				<th>Hotel</th>
				<th>Stars</th>
				<th>City</th>
				<th>Room Capacity</th>
				<th>Room View</th>
				<th>Room Price</th>
				<th>Number of Rooms Available</th>
				</tr>" ;
		
		while($row = $result->fetch_assoc()) {
			$Value = $row["room_id"] . "," . $row["hotel_id"] . "," . $row["capacity"] . "," . $row["price"];
			echo "<tr><td><input type=\"radio\" name=\"reserve\" value = \"$Value\"> " . 
					$row["hotel_group_id"] . "</td><td>" . $row["hotel_id"] . "</td><td>" . $row["stars"] . "</td><td>" . 
					$row["city"] . "</td><td>" . $row["capacity"] . "</td><td>" . $row["view"] . "</td><td>" . $row["price"] . " € </td><td>" . $row["num_rooms"] . "</td></tr>"; 
		}
			echo "</table>"; 
			echo "</div><br><br> 
			<div align = \"center\"> 
			<input type = \"submit\" value = \"Reserve\"> 
			</div>
			</form>
			<br>";	
		}
	
	
	else {
		echo "We are sorry: No results found <br>";
	}
	
	$conn->close();	
}	
			
?>





</body>
</html>
