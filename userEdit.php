<?php /**/
Session_start();
$item=$_GET["item"];
$conn = connectItemDB();
$exp = explode(":", $item);
if ($item== "display"){
	display($conn);
}
if ($exp[0] == "r"){
	removeUser($exp[1], $conn);
	display($conn);
}

if ($exp[0] == "a"){
	addUser($exp[1],$conn);
	display($conn);
}

function addUser($info, $conn){
	$info = explode(",", $info);
	$sql = "INSERT INTO `users`(username, password) VALUES ('$info[0]','$info[1]')";
	if ($conn->query($sql) === TRUE) {
		} else {
   		echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}


function removeUser($ID, $conn){
	$sql = "DELETE FROM users WHERE ID = '$ID'";
	if ($conn->query($sql) === TRUE) {
		} else {
   		echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}

function display($conn){
	$sql = "select * from users";
		$result = $conn->query($sql);
		echo "<Table cellpadding='10' border = '1'><tr><th>User ID</th><th>User Name</th></tr>";
		if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
		echo "<tr><td>" . $row['ID'] . "</td><td>" . $row['username']. 
			"</td><td>". "<button type='button' value='r:".$row['ID']."' onclick='action(this.value)'>Remove User</button>" . "</td></tr>"; 
	    }
	    echo "</table>";
	}
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


?>
