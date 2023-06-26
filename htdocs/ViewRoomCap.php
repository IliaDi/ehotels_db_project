<!DOCTYPE HTML>
<html>
<head>
<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 50%;
}

td, th {
    border-bottom: 1px solid #dddddd;
    text-align: left;
    padding: 15px;
}

tr:hover {background-color: #f5f5f5;}

</style>
</head>
<body>

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

$ViewQuery = " 	CREATE OR REPLACE VIEW room_capacities AS
				SELECT room_id, hotel_id, capacity
				FROM hotel_room
				ORDER BY capacity ; ";
				
if( !($result = $conn->query($ViewQuery)) ) {
			die("DataBase is temporarily unavailable. Query Failed: (" . $conn->errno . ") " . $conn->error);
		}

$query = " SELECT * FROM room_capacities ";

if( !($result = $conn->query($query)) ) {
	die("Query Failed: (" . $conn->errno . ") " . $conn->error);
}

if( $result->num_rows > 0 ) {
	echo  " <table id=\"example\" class=\"display\">
			<tr>
			<th>Room ID</th>
			<th>Hotel</th>
			<th>Room Capacity</th>
			</tr>" ;
	while($row = $result->fetch_assoc()) {
		echo "<tr><td>" . $row["room_id"] . "</td><td>" . $row["hotel_id"] . "</td><td>" . $row["capacity"] . "</td></tr>"; 
	}
		echo "</table>"; 
		echo "<br><br>";	
}
		
else {
		echo "We are sorry: No results found <br>";
	}

$conn->close();

?>

</body>
</html>