<?php
/**
* \file /api/v1/feedback/index.php
*
* \author Brian Hart
*/

  require("../lib/cls/Feedback.php");
  require("../lib/cls/Requests.php");

/** \brief Gets ticket from database based on interaction id
* \param interaction_id The identifier for what ticket to grab
* \returns The grabbed ticket
*/
function getSingleFeedback($interaction_id) {
  require("/var/database_config.php");
  $pdo = new PDO('mysql:host=' . $db_config['host'] . ';dbname=' . $db_config['dbname'], $db_config['username'], $db_config['password']);

  $sql = <<<SQL
SELECT * from feedback
  where interaction_id=?
  LIMIT 1
SQL;

  $statement = $pdo->prepare($sql);

  $statement->execute(array($interaction_id));
  if($statement->rowCount() === 0) {
    return null;
  }

  $row = $statement->fetch(PDO::FETCH_ASSOC);
  $feedback = new Feedback($row);
  return $feedback;
}


/** \brief Get all feedback associated with a given admin GM user
* \param admin_gm_id Identifier of user whose feedback is to be pulled
* \returns All feedback associated with admin GM user
*/
function getFeedback($admin_gm_id) {
  require("/var/database_config.php");
  $pdo = new PDO('mysql:host=' . $db_config['host'] . ';dbname=' . $db_config['dbname'], $db_config['username'], $db_config['password']);

  $sql = <<<SQL
SELECT * from feedback
  where admin_gm_id=? ORDER BY stars DESC
SQL;

  $statement = $pdo->prepare($sql);

  $statement->execute(array($admin_gm_id));
  if($statement->rowCount() === 0) {
    return null;
  }

  $total_feedback = array();
  while ($row = $statement->fetch(PDO::FETCH_ASSOC))
  {
    $feedback = new Feedback($row);
    $total_feedback[] = $feedback;
  }

  $requests = new Requests($total_feedback, Requests::Feedback);
  return $requests;
}

/** \brief Creates new feedback to be put in the database
* \param feedback Data required to make new feedback
* \returns A json encoded 'success' array on success, null in event of error
*/
function createFeedback($feedback) {
  try {
  require("/var/database_config.php");
  $pdo = new PDO('mysql:host=' . $db_config['host'] . ';dbname=' . $db_config['dbname'], $db_config['username'], $db_config['password']);

  $statement = $pdo->prepare("INSERT INTO feedback (interaction_id, admin_gm_id, stars, comments) VALUES (:interaction_id, :admin_gm_id, :stars, :comments)");


  $statement->bindParam(':interaction_id', $feedback['interaction_id']);
  $statement->bindParam(':admin_gm_id', $feedback['admin_gm_id']);
  $statement->bindParam(':stars', $feedback['stars']);
  $statement->bindParam(':comments', $feedback['comments']);

  $statement->execute();

  $log = array("Feedback left on ticket.");
  require("../log.php");
  writeLog($feedback['interaction_id'], $log);

  return array('status' => 'Successfully left feedback');
  }
  catch (PDOException $e) {
  return array('error' => 'Error leaving feedback!');
  }
}

// Create feedback with given json post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $raw_json = file_get_contents('php://input');
  $json = json_decode($raw_json, true);
  if($json !== null) {
    $create_feedback = createFeedback($json);
    if($create_feedback !== null) {
      header('Content-Type: application/json');
      http_response_code(201);
      echo json_encode($create_feedback);
      exit;
    }
    else {
      header('Content-Type: application/json');
      echo json_encode(['error' => 'Error: Feedback unable to be put into system!']);
      exit;
    }
  }
}
// Get all feedback associated with admin gm_id
else if(isset($_GET['admin_gm_id'])) {
  $admin_gm_id = trim($_GET['admin_gm_id']);

  $feedback = getFeedback($admin_gm_id);
  if($feedback !== null) {
    header('Content-Type: application/json');
    echo json_encode($feedback);
    exit;
  }
  else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error: GM ID not found in system!']);
    exit;
  }
}
// Get the feedback associated with interaction_id
else if (isset($_GET['interaction_id'])) {
  $interaction_id = trim($_GET['interaction_id']);

  $request = getSingleFeedback($interaction_id);
  if ($request !== null) {
    header('Content-Type: application/json');
    echo json_encode($request);
    exit;
  }
  else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error: Specified feedback does not exist!']);
    exit;
  } 
}
// In the case the request isn't covered...
else {
  header('Content-Type: application/json');
  echo json_encode(['error' => 'Error: Invalid request received!']);
  exit;
}
?>
