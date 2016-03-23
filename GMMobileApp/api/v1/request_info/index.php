<?php
/**
* \file /api/v1/request_info/index.php
*
* \author Brian Hart
*/

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $raw_json = file_get_contents('php://input');
  $json = json_decode($raw_json, true);
  if(isset($json['interaction_id']) && isset($json['message'])) {
    $return_switch = 0;

    $id = $json['interaction_id'];
    $additional = $json['message'];
    require("../push.php");
    $return_switch = pushNotifications($id, REQUEST_INFO, $additional);

    // If was able to push notification to database...
    if($return_switch === 1) {
      require("/var/database_config.php");
      $pdo = new PDO('mysql:host=' . $db_config['host'] . ';dbname=' . $db_config['dbname'], $db_config['username'], $db_config['password']);

      $statement = $pdo->prepare('UPDATE tickets SET dirty = ?, request = ? WHERE interaction_id = ?');
      $statement->execute(array("T", $additional, $id));
    
      require("../log.php");
      if ($additional === "") {
        writeLog($id, array("Additional information requested."));
      }
      else {
        writeLog($id, array("Required: " . $additional));
      }

      header('Content-Type: application/json');
      echo json_encode(['success' => 'Request for additional information successful!']);
      exit;
    }
    else if ($return_switch === 2) {
      header('Content-Type: application/json');
      echo json_encode(['error' => 'Error: Invalid push notification!']);
      exit;
    }
    else if ($return_switch === 0) {
      header('Content-Type: application/json');
      echo json_encode(['error' => 'Error: Script encountered unforseen error!']);
      exit;
    }
  }
  else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error: Invalid post!']);
    exit;
  }
}
?>
