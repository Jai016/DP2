<?php
Session_start();
$item=$_GET["item"];
$exp = explode(":", $item);
	if (!empty($_SESSION['Cart'])){
		if($exp[0] == "r"){
			RemoveItem($exp[1]);
			echo printCart();
		}
}
	
if ($exp[0] == "a"){
	AddItem($exp[1]);
	echo printCart();
		}elseif($item == "b:buy"){
			if (empty($_SESSION['Cart'])){
				echo "cart is empty";
				}else{
			checkStock();
			updateDB();
			stockWarning();
			saveRecipt();
		}
	}elseif($item == "b:inventory"){
		echo "inventory.html";
	}elseif($item == "load"){
		printItems();
	}

function stockWarning(){
	$conn = connectItemDB();
	$sql = "select * from items";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	    	if ($row['stock'] <= 10){
	    		echo "WARNING: Only ".$row['stock']." Remaining of item: ".$row['itemname']."!<br>";
	    	}
	    }
	}
}


function updateDB(){
	$conn = connectItemDB();
	$cart = $_SESSION['Cart'];
	foreach($cart as $row => $value) {
	$itemname = $row;
	$itemStock = $value['quantity'];
	$stockDB = itemStockDB($itemname);
	$remainingStock = $stockDB - $itemStock;
	$sql = "UPDATE items SET stock='$remainingStock' WHERE itemname = '$itemname'";
	if ($conn->query($sql) === TRUE) {
		} else {
   		echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
}

function itemStockDB($itemname){
	$conn = connectItemDB();
	$sql = "select * from items where itemname = '$itemname'";
	$result = $conn->query($sql);
			if ($result->num_rows > 0) {
	    		for($i = 0; $row = $result->fetch_assoc(); $i++) {
					$itemStock = $row['stock'];
			
					}
				}	
	return $itemStock;
}

function printItems(){
	$conn = connectItemDB();
	printItemsDB($conn);
	$conn->close();
	session_unset(); 
}

function connectItemDB(){
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
		echo "<table border = 'bold'>";
		if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
		echo "<tr><td>" . $row['itemname'] . "</td><td>" . $row['price'] . "</td><td>" . "<button type='button' value='a:".$row['itemname']."' onclick='action(this.value)'>Add Item</button>" . 
			"</td><td>". "<button type='button' value='r:".$row['itemname']."' onclick='action(this.value)'>Remove Item</button>" . "</td></tr>"; 
	    }
	    echo "</table>";
	}
}

function checkStock(){
	$conn = connectItemDB();
	$cart = $_SESSION['Cart'];
	$error = 0;
	foreach($cart as $row => $value) {
	    $itemname = $row;
	    $itemStock = $value['quantity'];
	    $sql = "select * from items where itemname = '$itemname'";
			
		$result = $conn->query($sql);
			if ($result->num_rows > 0) {
	    		for($i = 0; $row = $result->fetch_assoc(); $i++) {
					$dbStock = $row['stock'];
			
						if ($itemStock > $dbStock){
						echo "not enough stock for item: ".$itemname."! <br>"; 
						$error = 1;
						}
					}
				}
			}
		if ($error == 1){
		echo printCart();
		exit();
	}	
}

function saveRecipt(){
	$cart = $_SESSION['Cart'];
	$save = "";
	foreach($cart as $row => $value) {
	   $save = $save + $value['quantity'];
	}
	$save = (string)$save;
	$total = total();
	$conn = connectItemDB();
	$sql = "INSERT INTO `sales` (`time`, `soldItems`, `total`) VALUES (CURRENT_TIMESTAMP, '$save', '$total')";
		if ($conn->query($sql) === TRUE) {
    	echo "Sale was succsessfull";
		} else {
   		echo "Error: " . $sql . "<br>" . $conn->error;
		}
			
	$_SESSION['Cart'] = "";
	session_unset(); 
}


function printCart(){
	$total = 0;
	$cart = $_SESSION['Cart'];
	foreach($cart as $row => $value) {
       echo "Item Name: ".$row. " Quanitity: " . $value['quantity']. "<br>";
	}
	echo "Total Price: ".total();
}

function total(){
	$total = 0;
	$cart = $_SESSION['Cart'];
	foreach($cart as $row => $value) {
       $total = $total + $value['amount'];
}
	return $total;
}

function AddItem($item){
	$item = (string)$item; 
	if (isset($_SESSION['Cart'])){
		$cart = $_SESSION['Cart'];
			$keys = array_keys($cart);
				if (array_key_exists($item,$cart)){
 				$price = FindItemPrice_db($item);
				$cart[$item]['quantity'] = $cart[$item]['quantity']+1;
				$cart[$item]['amount'] = $cart[$item]['quantity']*$price;
  				}else{
  				$newcart = array($item =>array("quantity" => 1, 'amount' => FindItemPrice_db($item)));
				$cart = array_merge($cart, $newcart);
			}
			$_SESSION['Cart'] = $cart;		
		
	}else{
		$conn = connectItemDB();
		$sql = "select * from items where itemname = '$item'";
		$result = $conn->query($sql);

			if ($result->num_rows > 0) {
	    	// output data of each row
	    	while($row = $result->fetch_assoc()) {
	       $cart = array($item =>array("quantity" => 1, 'amount' => FindItemPrice_db($item)));
	    }
		} else {
	   		echo "Error finding item";
		}
		$_SESSION['Cart'] = $cart;
	}
}

function RemoveItem($item){
	$item = (string)$item; 
	if (isset($_SESSION['Cart'])){
		$cart = $_SESSION['Cart'];
			$price = FindItemPrice_db($item);
			if (array_key_exists($item,$cart)){

			$cart[$item]['quantity'] = $cart[$item]['quantity']-1;
			$cart[$item]['amount'] = $cart[$item]['quantity']*$price;			
				if ($cart[$item]['quantity'] == 0){
					unset ($cart[$item]);
				}
			$_SESSION['Cart'] = $cart;
		}
	}
}



function FindItemPrice_db($item){
	$conn = connectItemDB();
	$sql = "select * from items where itemname = '$item'";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	       $return = $row['price'] ;
	    }
	} else {
	    echo "Error finding item cost";
	}
return $return;
}

	function Itemsdb(){
	$csv = array_map('str_getcsv', file('items_db.csv'));
	return $csv;
	}


?>