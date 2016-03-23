<?php
/**
* \file /api/v1/log.php
*
* \author Brian Hart
*/

/** \brief Writes any changes to ticket to a log on the database
* \param interaction_id Identifier for the updated log
* \param log_str Array containing all updates performed on the string
* \returns True or False on whether or not log creation successful
*/
function writeLog($interaction_id, $log_str) {
  try {
  require("/var/database_config.php");
  $pdo = new PDO('mysql:host=' . $db_config['host'] . ';dbname=' . $db_config['dbname'], $db_config['username'], $db_config['password']);

  $date = date('m-d-y, h:i:s A T');

  $statement = $pdo->prepare("INSERT INTO interaction_log (interaction_id, date, description) VALUES (:interaction_id, :date, :description)");
  
  foreach($log_str as $log)
  {
    $statement->bindParam(':interaction_id', $interaction_id);
    $statement->bindParam(':date', $date);
    $statement->bindParam(':description', $log);

    $statement->execute();
  }

  return TRUE; 
  }
  catch (PDOException $e) {
  return FALSE;
  }
}

?>
