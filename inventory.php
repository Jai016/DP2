<?php
Session_start();
connect();
$action = $_GET['action'];
if ($action == 'display'){
	printtable();
}elseif ($action == "editName"){
	$newdata=$_GET['newdata'];
	$old=$_GET['old'];
	editName($newdata, $old);
	$newItem = $_GET['action'];
	printtable();
}elseif ($action == "editPrice"){
	$newdata=$_GET['newdata'];
	$old=$_GET['old'];
	editPrice($newdata, $old);
	$newItem = $_GET['action'];
	printtable();
}elseif ($action == "editStock"){
	$newdata=$_GET['newdata'];
	$old=$_GET['old'];
	editStock($newdata, $old);
	$newItem = $_GET['action'];
	printtable();
}elseif ($action == "deleterow"){
	$old=$_GET['old'];
	deleterow($old);
	printtable();
}else{
	$newItem = $_GET['action'];
	addItem($newItem);
	printtable();
}

function editName($newdata, $old){
	$conn=connect();
	$sql = "UPDATE items SET itemname='$newdata' WHERE itemname='$old';";
		$result = $conn->query($sql);
}

function editPrice($newdata, $old){
	$conn=connect();
	$sql = "UPDATE items SET price='$newdata' WHERE itemname='$old';";
		$result = $conn->query($sql);
}
function editStock($newdata, $old){
	$conn=connect();
	$sql = "UPDATE items SET stock='$newdata' WHERE itemname='$old';";
		$result = $conn->query($sql);
}
function deleterow($old){
	$conn=connect();
	$sql = "DELETE FROM items WHERE itemname='$old';";
		$result = $conn->query($sql);
}

function printtable(){
	$conn=connect();
	printItemsDB($conn);
	$conn->close();
}

function addItem($newItem){
	$item=explode(",", $newItem);
	$conn=connect();
	$sql = "INSERT INTO `items` (`itemname`, `price`, `stock`) VALUES ('$item[0]', '$item[1]', '$item[2]')";
	$result = $conn->query($sql);
}

function connect(){
	$servername = 'localhost';
	$dbname = 'php';
	$dbpassword = '';
	$dbuser = 'root';
	// Create connection
	$conn = new mysqli($servername, $dbuser, $dbpassword, $dbname);
	// Check connection
	if ($conn->connect_error) {
   	 die("Connection failed: " . $conn->connect_error);
	} 
	return $conn;
}

function printItemsDB($conn){
	$sql = "select * from items";
		$result = $conn->query($sql);
		echo "<Table cellpadding='10' border = '1'><tr><th>Item Name</th><th>Price</th><th>Stock</th></tr>";
		if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
		echo "<tr><td>" . $row['itemname'] . " " ."<button type='button' align='right' value='editName' name=".$row['itemname']." onclick='editItem(this.value,this.name)'>Edit</button>"."</td><td>". $row['price'] ." " ."<button type='button' align='right' value='editPrice' name=".$row['itemname']." onclick='editItem(this.value,this.name)'>Edit</button>"."</td><td>".$row['stock'] ."<button type='button' align='right' value='editStock' name=".$row['itemname']." onclick='editItem(this.value,this.name)'>Edit</button>"."</td><td>"."<button type='button' align='right' value='deleterow' name=".$row['itemname']." onclick='deleterow(this.value, this.name)'>Delete</button>"."</td></tr>"; 
	    }
	    echo "</table>";
	}
}


?>