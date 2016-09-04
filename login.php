<?php
if (!empty($_GET['userName'] and (!empty($_GET['password'])))){
	$username = $_GET['userName'];
	$password = $_GET['password'];	

	$userCheck = findUser($username, $password);
	if ( $userCheck == "True"){
	echo "asdf1234";

	}
}else{
	echo "missing fields";
}

function findUser($user, $password){
	$return = "";
	$itemsdb = userdb();
	$keys = array_keys($itemsdb);
	//for loop to display the array values
	for($i = 0; $i < count($itemsdb); $i++) {
  		  foreach($itemsdb[$keys[$i]] as $key => $value) {
        		if ($user == $value){
        			$return = $itemsdb[$i];
        		}
       	}
    }
	$return = implode(" ", $return);
	$return = explode(" ", $return);
	if ($return[0] = $user and $return[2] == $password){
		$return = "True";
	}else{
		$return = "False";
	}
	
	return $return;
}

function userdb(){
$csv = array_map('str_getcsv', file('users_db.csv'));
	return $csv;
}
?>