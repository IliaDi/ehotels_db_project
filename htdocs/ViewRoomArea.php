<!DOCTYPE HTML>
<html>
<head>
<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 40%;
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
<br><br><br>
<div align = "center">

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

$ViewQuery = "  CREATE OR REPLACE VIEW available_rooms_per_area AS
				SELECT city, SUM(number_of_rooms)
				FROM hotel
				GROUP BY city;";
				
if( !($result = $conn->query($ViewQuery)) ) {
			die("DataBase is temporarily unavailable. Query Failed: (" . $conn->errno . ") " . $conn->error);
		}

$query = " SELECT * FROM available_rooms_per_area ";

if( !($result = $conn->query($query)) ) {
	die("Query Failed: (" . $conn->errno . ") " . $conn->error);
}

if( $result->num_rows > 0 ) {
	echo  " <table id=\"example\" class=\"display\">
			<tr>
			<th>City</th>
			<th>Number of Rooms</th>
			</tr>" ;
	while($row = $result->fetch_assoc()) {
		echo "<tr><td>" . $row["city"] . "</td><td>" . $row["SUM(number_of_rooms)"] . "</td></tr>"; 
	}
		echo "</table>"; 
		echo "<br><br>";	
}
		
else {
		echo "We are sorry: No results found <br>";
	}

$conn->close();

?>

</div>
</body>
</html>