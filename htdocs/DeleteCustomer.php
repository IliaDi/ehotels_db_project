<!DOCTYPE HTML>
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>

<?php

$IRS =  "";
$ready = false;

if($_SERVER["REQUEST_METHOD"] == "POST") {
	
		$ready = true;
		if(!empty($_POST["irs"])) {
			$IRS = test_input($_POST["irs"]);	
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
	
		$query = "DELETE FROM customer WHERE customer_irs_number = '" . $IRS . "'";
		if( !($result = $conn->query($query)) ) {
				die("DataBase is temporarily unavailable. Query Failed: (" . $conn->errno . ") " . $conn->error);
			}
		else {
			if($conn->affected_rows > 0)
				echo "Delete was successful! <br>";
			else
				echo "Employee does not exists! <br>";
		}
		
}

?>


<h1><div align = "center">Delete a Customer</div></h1><br>
<br>
<br>

<form method="post" action="<?php echo 
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	
	 Enter Customer IRS Number: 
	<input type = "text" name = "irs" value = "<?php echo $IRS;?>" required>
	<br><br>
	<input type = "submit" value = "Delete Customer"><br><br>

	
</form>
</body>
</html>