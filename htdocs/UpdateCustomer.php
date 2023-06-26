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

$IRS = $SSN = $fname = $lname = $PostalCode = $city = $first_reg = "";
$street = $number = null;
$find = $update = false;

// Parse User Input 

if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	switch ($_POST["action"]) {
		case "find": 
				$find = true;
				if(!empty($_POST["irs"])) {
					$IRS = test_input($_POST["irs"]);	
				}
		break;
		
		case "update":
					
				$update = true;
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
	
	$_SESSION["irs"] = $IRS;
	$query = "SELECT * FROM customer WHERE customer_irs_number = '" . $IRS . "'";
	if( !($result = $conn->query($query)) ) {
			die("DataBase is temporarily unavailable. Query Failed: (" . $conn->errno . ") " . $conn->error);
		}

	if( $result->num_rows > 0 ) {
		$row = $result->fetch_assoc(); 
		$SSN = $row["social_security_number"]; 
		$fname = $row["first_name"]; 
		$lname = $row["last_name"];
		$street = $row["street"];
		$number = $row["number"];
		$PostalCode = $row["postal_code"];
		$city = $row["city"];
		$first_reg = $row["first_registration"];
	}
	else {
		echo "No Customer with this IRS found. <br>";
		echo $_SESSION["irs"] . "<br>";
	}
	
	$find = false;
}


?>

<h1><div align = "center">Customer's Information</div></h1><br>
<br>
<br>
<div align = "center">
<div class="solid">
<br>
<form method="post" action="<?php echo 
htmlspecialchars($_SERVER["PHP_SELF"]);?>">

	Customer IRS Number:
	<input type = "text" name = "irs" value = "<?php echo $IRS;?>"> *
	<br><br>
	</div><br><br>
	<input type="hidden" name="action" value="find">
	<input type="submit" value="Find Customer"><br><br><br><br>
	
</form>
</div>

<div align = "center">
<div class="solid">
<br><br>
<form method="post" action="<?php echo 
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	
	Customer Social Security Number * <br> 
	<input type = "text" name = "ssn" value = "<?php echo $SSN; ?>" required> 
	<br><br>
	First Name * <br>
	<input type = "text" name = "first_name"  value = "<?php echo $fname;?>" required> 
	<br><br>
	Last Name * <br>
	<input type = "text" name = "last_name"  value = "<?php echo $lname;?>"required> 
	<br><br>
	Street <br>
	<input type = "text" name = "street"  value = "<?php echo $street;?>">
	<br><br>
	Number  <br>
	<input type = "text" name = "number"  value = "<?php echo $number;?>">
	<br><br>
	Postal Code * <br>
	<input type = "text" name = "postal_code"  value = "<?php echo $PostalCode; ?>" required> 
	<br><br>
	City * <br>
	<input type = "text" name = "city"  value = "<?php echo $city; ?>" required> 
	<br><br>
	Fisrt Registration Date <br>
	<?php echo $first_reg; ?>
	<br><br><br></div><br></div>
	<div align = "center">
	<input type="hidden" name="action" value="update">
	<input type = "submit" value = "Update">
	</div>
	<br><br>


<?php

if($update) {
	$query = "UPDATE customer
			  SET social_security_number = (?), 
				  first_name = (?), 
				  last_name = (?), 
				  street = (?), 
				  number = (?), 
				  postal_code = (?),
				  city = (?)
			  WHERE customer_irs_number = (?)";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("ssssiiss", $SSN, $fname, $lname, $street, $number, $PostalCode, $city, $_SESSION["irs"]);
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