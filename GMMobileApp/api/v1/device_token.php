<?php
/**
* \file /api/v1/device_token.php
*
* \author Brian Hart
*/

/** \brief Update the device token for a given user
* \param gmid The indentifier for the user whose device token we are updating
* \param token The new token for a user (generated at app installation)
* \returns A success json upon success, error json upon failure
*/
function updateToken($gmid, $token) {
  try {
  $pdo = new PDO('mysql:host=localhost;dbname=gm', 'root', 'GMteammsu23679');
  
  $statement = $pdo->prepare("UPDATE users SET device_token = :token WHERE gm_id=:gm_id");
  
  $statement->bindParam(':token', $token);
  $statement->bindParam(':gm_id', $gmid);
  $statement->execute();
  
  return array('status' => 'Successful token update');
  }
  catch (PDOException $e) {
  return array ('error' => 'Error updating the ticket in the database');
  }
}

// Looking for a post request
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  $raw_json = file_get_contents('php://input');
  $json = json_decode($raw_json, true);
  if($json !== null) {
    // Updating token requires gm_id and token within the post
    $result = updateToken($json["gm_id"], $json["token"]);
    if($result !== null) {
      header('Content-Type: application/json');
      echo json_encode($result);
      exit;
    } else {
      header('Content-Type: application/json');
      echo json_encode(['error' => 'Login failed']);
      exit;
    }
  }
}
?>
