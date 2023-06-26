<!DOCTYPE HTML>
<html>
<body>
<font face="serif" color="gray">

<?php 

$hotel = $capacity = $price = $room_id = $days = "";
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
	$aux = explode(",", $_POST["reserve"]);
	$_SESSION["room_id"] = $aux[0];
	$_SESSION["hotel"] = $aux[1];
	$_SESSION["capacity"] = $aux[2];
	$_SESSION["price"] = $aux[3];
}

?>

<h1><div align = "center">Reservation</div></h1>
<div align = "center"> 
<style> 
body  {
    background-color: white;
} 

p.note {
	font-size: 12px;
}

.solid {
	border: 2px solid gray;
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


input[type=text] {
    width: 50%;
    padding: 5px 10px;
    margin: 8px 0;
    box-sizing: border-box;
    border: 1px solid #f1f1f1;
    border-radius: 2px;
}

select {
    width: 25%;
    padding: 7px 10px;
    border: none;
    border-radius: 5px;
    background-color: #f1f1f1;
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
  border-radius: 20px;
  box-shadow: 0 1px #999;
  opacity: 1;
}

input[type=submit]:hover {
	opacity: 0.7;
}
</style>
<form method="post" action="/Reserved.php">
<div class="solid"><br>
<b>Hotel</b><br>
<?php echo $_SESSION["hotel"]; ?>
<br><br>
<b>Room Information</b><br>
<?php switch($_SESSION["capacity"]) {
			case 1:
				$s = "Single Room";
				 break;
			case 2:
				$s = "Double Room";
			case 3:
				$s = "Junior Suite";
				break;
			case 4:
				$s = "Exectuive Suite";
				break;
		}
		
		echo $s . " - " . $_SESSION["price"] . " € per Night";
?>
<br><hr>
First Name <br>
<input type="text" name="fname" required><br>
Last Name <br>
<input type="text" name="lname" required><br>
<p class="note">Don't have an account? Please <a href="/SignUp.php" target="_blank" style="color:dodgerblue"> Sign Up </a>.</p>
<br>
Check in: <input type="date" name="InDate"/><br><br>
Check out: <input type="date" name="OutDate"/><br><br><br>

Are you paying with?<br><br>
<select name="payment">
		<option value="card">Card</option>
		<option value="cash on arrival">Cash on Arrival</option>
</select>
<br><br><br>
</div>

<br>
<input type="submit" value="Book">
</form>

<br>
<form method="post" action="/home.html">
<input type="submit" value="Cancel">
</form>

<!-- an den einai diathesimo  emfanizeis minima na dialeksei nees imerominies-->
</font>
</div>

</body>
</html>