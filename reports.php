<?php

Session_start();
$conn = connect();
printItemsDB($conn);

$action = $_GET['action'];
if ($action == "export"){
	export($conn);
}else{

}
$conn->close();

function export($conn){
		$sql = "SELECT YEAR(time), MONTH(time), SUM(soldItems),SUM(total) 
		FROM sales 
		GROUP BY YEAR(`time`), MONTH(`time`) 
		ORDER BY time DESC";
		
		$row = $conn->query($sql);
		
		$fp = fopen('monthlyReport.csv', 'w');
		$heading = array('Year','Month','soldItems','Total Sales');
		fputcsv($fp, $heading);
		foreach ($row as $val) {
   		fputcsv($fp, $val);
	}
	echo "Report has been exported";
	fclose($fp);
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
	$sql = "SELECT YEAR(time) as value1, MONTH(time) as value2, SUM(`total`) AS value3, SUM(soldItems) AS value4 
		FROM sales 
		GROUP BY YEAR(`time`), MONTH(`time`) 
		ORDER BY time DESC";
		$result = $conn->query($sql);
		echo "<Table cellpadding='10' border = '1'><tr><th>Date</th><th>Total Items Sold</th><th>Monthly Total</th></tr>";
		if ($result->num_rows > 0) {
	    // output data of each row
	    for($i = 0; $row = $result->fetch_assoc(); $i++) {
		echo "<tr><td>" . $row['value1']."/".$row['value2']."</td><td>".$row['value4']."</td><td>".$row['value3']."</td></tr>";
	    }
	    echo "</table>";
	}
}

?>