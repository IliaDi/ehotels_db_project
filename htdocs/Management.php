<!DOCTYPE HTML>
<html>
<head>
<style>
.error {color: #FF0000;}


.solid {
	border: 2px solid gray;
	border-radius: 4px; 
	width: 30%; }

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


<h1><div align = "center">Management</div></h1><br>
<br>
<br>
<div align = "center">
<form method="post" action="<?php echo 
htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	
	<big>Desired Action:</big><br><br><br>
	<div class="solid"><br><br>
	
	<input type = "radio" name = "Manage" value = "InsertHotel">     Add a new Hotel to our Group <br>	 	
	<input type = "radio" name = "Manage" value = "UpdateHotel"> 	 Update Information about a Hotel <br>	 	
	<input type = "radio" name = "Manage" value = "DeleteHotel"> 	 Delete Hotel <br> <br>
	
	<input type = "radio" name = "Manage" value = "InsertRoom"> 	 Add a new Room <br> 
	<input type = "radio" name = "Manage" value = "UpdateRoom"> 	 Update Information about a Room <br>
	<input type = "radio" name = "Manage" value = "DeleteRoom"> 	 Delete Room <br> <br>
	
	<input type = "radio" name = "Manage" value = "InsertEmployee">  Hire an Employee    <br>
	<input type = "radio" name = "Manage" value = "UpdateEmployee">  Update Information about an Employee  <br>
	<input type = "radio" name = "Manage" value = "DeleteEmployee">  Fire an Employee   <br> <br>
	
	<input type = "radio" name = "Manage" value = "InsertCustomer">  Register a new Customer   <br>
	<input type = "radio" name = "Manage" value = "UpdateCustomer">  Update Information about a Customer <br> 	
	<input type = "radio" name = "Manage" value = "DeleteCustomer">  Delete a Customer   <br> <br>
	
	<br>
	<br></div></div><br>
		<div align = "center">
		<input type="submit" value="Proceed">
		</div>
	<br><br>


<?php

// Parse User Input 

if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	$Manage = "";
	if(isset($_POST["Manage"])) {
		$Manage = test_input($_POST["Manage"]);
		$Page = "Location: /" . $Manage . ".php";
		header($Page);
		exit;
	}
}
	
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>




</body>
</html>
