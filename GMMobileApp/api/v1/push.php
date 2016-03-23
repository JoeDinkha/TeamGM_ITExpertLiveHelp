<?php
/**
* \file /api/v1/push.php
*
* \author Brian Hart
*/

define("REQUEST_INFO", 2);
define("UPDATED", 1);

/** \brief Writes any changes to ticket to a log on the database
* \param interaction_id Identifier of ticket to be associated with the push
* \returns Array containing success json on success, null on failure
*/
function pushNotifications($interaction_id, $type, $additional) {
  require("/var/database_config.php");
  $pdo = new PDO('mysql:host=' . $db_config['host'] . ';dbname=' . $db_config['dbname'], $db_config['username'], $db_config['password']);

  $statement = $pdo->prepare('SELECT gm_id FROM tickets WHERE interaction_id = ? LIMIT 1');
  $statement->execute(array($interaction_id));

  $id = $statement->fetchColumn();

  if ($id !== null) {
    $statement = $pdo->prepare('SELECT device_token from users WHERE gm_id = ? LIMIT 1');
    $statement->execute(array($id));

    $token = $statement->fetchColumn();

    // if the token is valid...
    if ((strlen($token) == 64) && (preg_match("/^[0-9a-fA-F]{64}$/", $token) != 0)) {
      if ($type === UPDATED) {
        $text = 'Ticket ' . $interaction_id . ' has been updated.';
      }
      else if ($type === REQUEST_INFO) {
        if ($additional === '') {
          $text = 'Additional info requested for ticket ' . $interaction_id . '.';
        }
        else {
          $text = 'Additional info requested for ticket ' . $interaction_id . ': ' . $additional;
        }
      }
      $textJson = json_encode($text);
	
      $payload = '{"aps":{"alert":' . $textJson . ', "badge":1, "sound":"default"}}';

      // if the payload is within the allowed size of Apple's push server...
      if (strlen($payload) <= 256) {
        $statement = $pdo->prepare('INSERT INTO push_queue (device_token, payload, time_queued) VALUES (?, ?, NOW())');
	$statement->execute(array($token, $payload));
      }
      return 1;
    }
  }
  // If here, push notification not queued, return fail
  return 2;
}

?>
