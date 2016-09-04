<?php
$item=$_GET["item"];


$file = fopen("items_db.csv","r");
while(! feof($file))
  {
  print_r(fgetcsv($file));
  }

fclose($file);


$cart = array($item=>array("quantity"=>1,"amount" => 5.50));

// creates key from $cart array
$keys = array_keys($cart);
//for loop to display the array values
for($i = 0; $i < count($cart); $i++) {
	//echos item name then loops though each value
    echo "Item Name: ". $keys[$i]. "<br>";
    foreach($cart[$keys[$i]] as $key => $value) {
        echo $key ." ". $value , "<br>";
    }
    echo "<br>";
}
?>