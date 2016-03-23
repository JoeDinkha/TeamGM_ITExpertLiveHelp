<?php
/**
* \file /api/v1/users/index.php
*
* \author Brian Hart
*/

  require("../lib/cls/UserData.php");

/** \brief Gets tickets for admins; must not be closed/canceled, and not 
*          already assigned to another admin
* \returns All tickets meeting the aforementioned criteria
*/
function getAllUsers() {
  require("/var/database_config.php");
  $pdo = new PDO('mysql:host=' . $db_config['host'] . ';dbname=' . $db_config['dbname'], $db_config['username'], $db_config['password']);

  $sql = <<<SQL
SELECT * from users
SQL;

  $statement = $pdo->prepare($sql);

  $statement->execute();
  if($statement->rowCount() === 0) {
    return null;
  }

  $users = array();
  while ($row = $statement->fetch(PDO::FETCH_ASSOC))
  {
    $user = new UserData($row);

     //Pull user schedule for next 24 hrs
      $pos = strpos($row['email'],"@gmcapstone.com");
      if($pos !== false){
	$data = array('email' => $row['email'], 'password' =>$row['password']);
	$ch = curl_init("http://35.9.22.209/availability.php");
        curl_setopt_array($ch, array(
          CURLOPT_POST=>TRUE,
          CURLOPT_RETURNTRANSFER=>TRUE,
          CURLOPT_HTTPHEADER=> array('Content-Type: application/json'),
          CURLOPT_POSTFIELDS => json_encode($data),
    	  ));
        $response = curl_exec($ch);
	$response = json_decode($response);
	if($response!==""){
	  $expl_resp = explode("  ", $response);
	  $user->schedule = $expl_resp;
	}
      }


    $users[] = $user;
  }

  $users_complete = array("users" => $users);
  return $users_complete;
}

// Get all user information
if ($_SERVER['REQUEST_METHOD'] == 'GET'){
  $all = getAllUsers();
  if ($all !== null) {
    header('Content-Type: application/json');
    echo json_encode($all);
    exit;
  }
  else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error: Couldnt get all users!']);
    exit;
  }
}
else {
  header('Content-Type: application/json');
  echo json_encode(['error' => 'Error: Invalid request received!']);
  exit;
}
?>
