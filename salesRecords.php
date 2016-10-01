<?php /**/

Session_start();
$conn = connect();
printItemsDB($conn);

$action = $_GET['action'];
if ($action == "export"){
	export($conn);
}else{

}

function export($conn){
		$sql = "SELECT * FROM `sales` WHERE DATE(time) = CURDATE()";
		
		$row = $conn->query($sql);
		$date = date('Ym');
		$fp = fopen("daily report".$date.".csv", 'w');
		$heading = array('DateTime','soldItems','Total Sales');
		fputcsv($fp, $heading);
		foreach ($row as $val) {
   		fputcsv($fp, $val);
	}
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
	$sql = "select * from sales WHERE DATE(time) = CURDATE() ORDER BY time DESC";
		$result = $conn->query($sql);
		echo "<Table cellpadding='10' border = '1'><tr><th>Date</th><th>Quantity Sold</th><th>Total</th></tr>";
		if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
		echo "<tr><td>" . $row['time']."</td><td>". $row['soldItems']."</td><td>".$row['total']."</td></tr>"; 
	    }
	    echo "</table>";
	}
}

?>
