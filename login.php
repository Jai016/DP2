<?php /**/
if (!empty($_GET['userName'] and (!empty($_GET['password'])))){
	$username = $_GET['userName'];
	$password = $_GET['password'];	

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
	$sql = "select * from users where username = '$username' and password = '$password'";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	       echo 'true';
	    }
	} else {
	    echo "Error with username or password";
	}
	$conn->close();

}
?>
