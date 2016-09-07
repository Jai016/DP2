<?php
Session_start();

$action = $_GET['action'];
$itemdb = Itemsdb();
if ($action == 'display'){
	echo printCart($itemdb);
}else{
	//echo $action;
	//addItemToDB($action);
	echo printCart($itemdb);
}


function Itemsdb(){
$csv = array_map('str_getcsv', file('items_db.csv'));
return $csv;
}

function printCart($db){
	$cart = $db;
	echo "<Table border = '1'><tr><th>Item Name</th><th>Price</th><th>Stock</th></tr>";
		foreach($cart as $row => $value) {
	echo "<tr>";
    echo "<td>" .$value[0]. "</td><td>" . $value[1] . "</td><td>". $value[2]. "</td>";
    echo "</tr>";
	}
	echo "</Table>";
}

//function addItemToDB($action){
//	$itemdb = Itemsdb();
//	$newItems= explode(",", $action);
//		$cart = array($newItems[0] =>array("quantity" => 1, 'amount' => $itemData[2]));
//		$_SESSION['Cart'] = $cart;
	//foreach($itemdb as $row => $value) {
    //   echo $row. $value[0].$value[1];
	//}
//}

function saveRecipt(){
	$cart = $_SESSION['Cart'];
	$save = "";
	foreach($cart as $row => $value) {
   		$save = $save.$row.",".$value['quantity'].",".$value['amount'].",";
		}
	$save = $save.total(). "\n";
	$myfile = fopen("saveData.csv", "a");
	fwrite($myfile, $save);
	fclose($myfile);
	$_SESSION['Cart'] = "";
	session_unset(); 
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
		$itemData = FindItem_db($item);
		$itemData = implode(" ", $itemData);
		$itemData = explode(" ", $itemData);
		$cart = array($itemData[0] =>array("quantity" => 1, 'amount' => $itemData[2]));
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

function FindItem_db($item){
	$return = "";
	$itemsdb = Itemsdb();
	$keys = array_keys($itemsdb);
	//for loop to display the array values
	for($i = 0; $i < count($itemsdb); $i++) {
  		  foreach($itemsdb[$keys[$i]] as $key => $value) {
        		if ($item == $value){
        			$return = $itemsdb[$i];
        		}
        	}
       	}
    

return $return;
}

function FindItemPrice_db($item){
	$return = "";
	$itemsdb = Itemsdb();
	$keys = array_keys($itemsdb);
	//for loop to display the array values
	for($i = 0; $i < count($itemsdb); $i++) {
  		  foreach($itemsdb[$keys[$i]] as $key => $value) {
        		if ($item == $value){
        			$return = $itemsdb[$i];
        		}
       	}
    }
	$return = implode(" ", $return);
	$return = explode(" ", $return);
	$return = $return[2];
return $return;
}




?>