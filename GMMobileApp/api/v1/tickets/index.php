<?php
/**
* \file /api/v1/tickets/index.php
*
* \author Brian Hart
*/

  //require("../../../lib/site.inc.php");
  require("../lib/cls/Ticket.php");
  require("../lib/cls/Requests.php");
  require("../lib/cls/Log.php");

/** \brief Gets the last ticket from ticket database
* \returns The last ticket
*/
function getLast() {
  require("/var/database_config.php");
  $pdo = new PDO('mysql:host=' . $db_config['host'] . ';dbname=' . $db_config['dbname'], $db_config['username'], $db_config['password']);

  $sql = <<<SQL
SELECT interaction_id from tickets
  ORDER BY interaction_id DESC LIMIT 1
SQL;
  
  $statement = $pdo->prepare($sql);
  $statement->execute();

  if($statement->rowCount() === 0) {
    return null;
  }

  $interaction_id = $statement->fetch(PDO::FETCH_ASSOC);
  return $interaction_id;
} 

/** \brief Gets ticket from database based on interaction id
* \param interaction_id The identifier for what ticket to grab
* \returns The grabbed ticket
*/
function getRequest($interaction_id) {
  require("/var/database_config.php");
  $pdo = new PDO('mysql:host=' . $db_config['host'] . ';dbname=' . $db_config['dbname'], $db_config['username'], $db_config['password']);

  $sql = <<<SQL
SELECT * from tickets
  where interaction_id=?
  LIMIT 1
SQL;

  $statement = $pdo->prepare($sql);

  $statement->execute(array($interaction_id));
  if($statement->rowCount() === 0) {
    return null;
  }

  $row = $statement->fetch(PDO::FETCH_ASSOC);
  $ticket = new Ticket($row);
  return $ticket;
}

/** \brief Gets tickets for admins; must not be closed/canceled, and not 
*          already assigned to another admin
* \returns All tickets meeting the aforementioned criteria
*/
function getAll() {
  require("/var/database_config.php");
  $pdo = new PDO('mysql:host=' . $db_config['host'] . ';dbname=' . $db_config['dbname'], $db_config['username'], $db_config['password']);

  $sql = <<<SQL
SELECT * from tickets where admin_gm_id="" and status!="Closed" and status!="Canceled" ORDER BY last_update_date DESC
SQL;

  $statement = $pdo->prepare($sql);

  $statement->execute();
  if($statement->rowCount() === 0) {
    return null;
  }

  $tickets = array();
  while ($row = $statement->fetch(PDO::FETCH_ASSOC))
  {
    $ticket = new Ticket($row);
    $tickets[] = $ticket;
  }

  $requests = new Requests($tickets, Requests::Tickets);
  return $requests;
}

/** \brief Gets an admin's personal queue tickets
* \param admin_gm_id Identifier for tickets worked on by admin
* \returns All tickets assigned to the admin
*/
function getPersonal($admin_gm_id) {
  require("/var/database_config.php");
  $pdo = new PDO('mysql:host=' . $db_config['host'] . ';dbname=' . $db_config['dbname'], $db_config['username'], $db_config['password']);

  $sql = <<<SQL
SELECT * from tickets
  where admin_gm_id=? and status!="Closed" and status!="Canceled" ORDER BY last_update_date DESC
SQL;

  $statement = $pdo->prepare($sql);

  $statement->execute(array($admin_gm_id));
  if($statement->rowCount() === 0) {
    return null;
  }

  $tickets = array();
  while ($row = $statement->fetch(PDO::FETCH_ASSOC))
  {
    $ticket = new Ticket($row);
    $tickets[] = $ticket;
  }

  $personal = new Requests($tickets, Requests::Tickets);
  return $personal;
}

/** \brief Gets the logs associated with a given ticket
* \param interaction_id Identifier of ticket whose logs are to be grabbed
* \returns All logs associated with the ticket
*/
function getLogs($interaction_id) {
  require("/var/database_config.php");
  $pdo = new PDO('mysql:host=' . $db_config['host'] . ';dbname=' . $db_config['dbname'], $db_config['username'], $db_config['password']);

  $sql = <<<SQL
SELECT * from interaction_log
  where interaction_id=?
SQL;

  $statement = $pdo->prepare($sql);

  $statement->execute(array($interaction_id));
  if($statement->rowCount() === 0) {
    return null;
  }

  $logs = array();
  while ($row = $statement->fetch(PDO::FETCH_ASSOC))
  {
    $log = new Log($row);
    $logs[] = $log;
  }

  $interaction_logs = new Requests($logs, Requests::Logs);
  return $interaction_logs;
}

/** \brief Get all tickets associated with a given GM user
* \param gm_id Identifier of user whose tickets are to be pulled
* \returns All tickets associated with GM user
*/
function getRequests($gm_id) {
  require("/var/database_config.php");
  $pdo = new PDO('mysql:host=' . $db_config['host'] . ';dbname=' . $db_config['dbname'], $db_config['username'], $db_config['password']);

  $sql = <<<SQL
SELECT * from tickets
  where gm_id=?
SQL;

  $statement = $pdo->prepare($sql);

  $statement->execute(array($gm_id));
  if($statement->rowCount() === 0) {
    return null;
  }

  $tickets = array();
  while ($row = $statement->fetch(PDO::FETCH_ASSOC))
  {
    $ticket = new Ticket($row);
    $tickets[] = $ticket;
  }

  $requests = new Requests($tickets, Requests::Tickets);
  return $requests;
}

/** \brief Creates a new ticket to be put in the database
* \param ticket Data required to make a new ticket
* \returns A json encoded 'success' array on success, null in event of error
*/
function createTicket($ticket) {
  try {
  require("/var/database_config.php");
  $pdo = new PDO('mysql:host=' . $db_config['host'] . ';dbname=' . $db_config['dbname'], $db_config['username'], $db_config['password']);


  $prep_str = "INSERT INTO tickets (admin_gm_id, affected_service, category, creation_date, description, gm_id, interaction_id, issue, last_update_date, notify_type, phase, status) VALUES (:admin_gm_id, :affected_service, :category, :creation_date, :description, :gm_id, :interaction_id, :issue, :last_update_date, :notify_type, :phase, :status)";

  $statement = $pdo->prepare($prep_str);

  $statement->bindParam(':admin_gm_id', $ticket['admin_gm_id']);
  $statement->bindParam(':affected_service', $ticket['affected_service']);
  $statement->bindParam(':category', $ticket['category']);
  $statement->bindParam(':creation_date', $ticket['creation_date']);
  $statement->bindParam(':description', $ticket['description']);
  $statement->bindParam(':gm_id', $ticket['gm_id']);
  $statement->bindParam(':interaction_id', $ticket['interaction_id']);
  $statement->bindParam(':issue', $ticket['issue']);
  $statement->bindParam(':last_update_date', $ticket['last_update_date']);
  $statement->bindParam(':notify_type', $ticket['notify_type']);
  $statement->bindParam(':phase', $ticket['phase']);
  $statement->bindParam(':status', $ticket['status']);

  $statement->execute();

  $log = array("Ticket created.");
  require("../log.php");
  writeLog($ticket['interaction_id'], $log);

  return array('status' => 'Successful creation');
  }
  catch (PDOException $e) {
  return array('error' => 'Error inserting ticket into the database!');
  }
}

/** \brief Gets user information based on GM id
* \param gm_id Identifier of user to be found
* \returns An array containing user information (email, name, phone number)
*/
function getUser($gm_id) {
  require("/var/database_config.php");
  $pdo = new PDO('mysql:host=' . $db_config['host'] . ';dbname=' . $db_config['dbname'], $db_config['username'], $db_config['password']);

  $sql = <<<SQL
SELECT * from users
  where gm_id=?
  LIMIT 1
SQL;

  $statement = $pdo->prepare($sql);

  $statement->execute(array($gm_id));
  if($statement->rowCount() === 0) {
    return null;
  }

  $row = $statement->fetch(PDO::FETCH_ASSOC);
  return array('email' => $row['email'], 'first_name' => $row['first_name'], 'last_name' => $row['last_name'], 'mobile_phone' => $row['mobile_phone'], 'work_phone' => $row['work_phone']);
}

/** \brief Updates a ticket with given information
* \param update Array containing fields of ticket to be updated, as well as
*               the interaction_id to locate the ticket
* \returns A json encoded 'success' array on success, null in event of error
*/
function updateTicket($update) {
  try {
  require("/var/database_config.php");
  $pdo = new PDO('mysql:host=' . $db_config['host'] . ';dbname=' . $db_config['dbname'], $db_config['username'], $db_config['password']);

  $sql_str = "UPDATE tickets SET ";
  $logs = array();
  $return_switch = 0; // For efficiency, must do push before update now
  $exec_array = array();
  
  if(isset($update['affected_service'])) {
    $sql_str .= "affected_service = ?, ";
    $exec_array[] = $update['affected_service'];
    $logs[] = "Updated affected service to '" . $update['affected_service'] . "'.";
  }
  if(isset($update['category'])) {
    $sql_str .= "category = ?, ";
    $exec_array[] = $update['category'];
  }
  if(isset($update['description'])) {
    $sql_str .= "description = ?, ";
    $exec_array[] = $update['description'];
    $logs[] = "Changed description to '" . $update['description'] . "'.";
  }
  if(isset($update['issue'])) {
    $sql_str .= "issue = ?, ";
    $exec_array[] = $update['issue'];
  }
  if(isset($update['notify_type'])) {
    $sql_str .= "notify_type = ?, ";
    $exec_array[] = $update['notify_type'];
  }
  if(isset($update['phase'])) {
    $sql_str .= "phase = ?, ";
    $exec_array[] = $update['phase'];
    $logs[] = "Phase changed to '" . $update['phase'] . "'.";
  }
  if(isset($update['status'])) {
    $sql_str .= "status = ?, ";
    $exec_array[] = $update['status'];
    $logs[] = "Status changed to '" . $update['status'] . "'.";
  }
  if(isset($update['admin_gm_id'])) {
    $sql_str .= "admin_gm_id = ?, ";
    $exec_array[] = $update['admin_gm_id'];
  }
  if(isset($update['notes'])) {
    $sql_str .= "notes = ?, ";
    $exec_array[] = $update['notes'];
  }
  if(isset($update['last_update_date'])) {
    $sql_str .= "last_update_date = ?, ";
    $exec_array[] = $update['last_update_date'];
  }
  else {
    $sql_str .= "last_update_date = ?, ";
    $date = date('m-d-y, h:i:s A T');
    $exec_array[] = $date;
  }
  if(isset($update['dirty'])) {
    $sql_str .= "dirty = ?, ";
    $exec_array[] = $update['dirty'];
  }
  else {
    //request_info reset
    $sql_str .= "request = ?, ";
    $exec_array[] = NULL;
  }
  if(isset($update['feedback'])) {
    $sql_str .= "feedback = ?, ";
    $exec_array[] = $update['feedback'];
  }
  if(isset($update['push'])) {
    require("../push.php");
    $return_switch = pushNotifications($update['interaction_id'], UPDATED, '');
    if ($return_switch == 1) {
      $sql_str .= "dirty = ?, ";
      $exec_array[] = "T";
    }
  }

  $sql_str = rtrim($sql_str, ", \t\n");

  $sql_str .= " WHERE interaction_id = ?";
  $exec_array[] = $update['interaction_id']; 
  
  $statement = $pdo->prepare($sql_str);

  $statement->execute($exec_array);

  require("../log.php");
  writeLog($update['interaction_id'], $logs);
  
  if ($return_switch == 0) {
    return array('status' => 'Successful update');
  }
  else if ($return_switch == 1) {
    return array('status' => 'Successful update, push notification queued');
  }
  else if ($return_switch == 2) {
    return array('status' => 'Successful update, invalid push notification.');
  }
  }
  catch (PDOException $e) {
  return array ('error' => 'Error updating the ticket in the database');
  }
}

// If data is posted...
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $raw_json = file_get_contents('php://input');
  $json = json_decode($raw_json, true);
  if($json !== null) { 
    $pre_exist_check = getRequest($json['interaction_id']);
    // Insert ticket
    if ($pre_exist_check === null)
    {
      $create_ticket = createTicket($json);
      if($create_ticket !== null) {
        header('Content-Type: application/json');
        http_response_code(201);
        echo json_encode($create_ticket);
        exit;
      }
      else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Error: Ticket ID already in system!']);
        exit;
      }
    }
    // Update ticket
    else
    {
      $update_ticket = updateTicket($json);
      if($update_ticket !== null) {
        header('Content-Type: application/json');
        http_response_code(201);
        echo json_encode($update_ticket);
        exit;
      }
      else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Error: Unable to update specified ticket!']);
        exit;
      }
    }
  }
}
// Get all tickets associated with gm_id
else if(isset($_GET['gm_id'])) {
  $gm_id = trim($_GET['gm_id']);

  $requests = getRequests($gm_id);
  if($requests !== null) {
    header('Content-Type: application/json');
    echo json_encode($requests);
    exit;
  }
  else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error: GM ID not found in system!']);
    exit;
  }
}
// Get all tickets associated with admin_gm_id
else if(isset($_GET['admin_gm_id'])) {
  $admin_gm_id = trim($_GET['admin_gm_id']);

  $requests = getPersonal($admin_gm_id);
  if($requests !== null) {
    header('Content-Type: application/json');
    echo json_encode($requests);
    exit;
  }
  else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error: Admin ID not found in system!']);
    exit;
  }
}
// Get the ticket associated with interaction_id
else if (isset($_GET['interaction_id'])) {
  $interaction_id = trim($_GET['interaction_id']);

  $request = getRequest($interaction_id);
  if ($request !== null) {
    header('Content-Type: application/json');
    echo json_encode($request);
    exit;
  }
  else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error: Specified ticket does not exist!']);
    exit;
  } 
}
// Get the logs associated with a given ticket
else if (isset($_GET['log'])) {
  $interaction_id = trim($_GET['log']);

  $request = getLogs($interaction_id);
  if ($request !== null) {
    header('Content-Type: application/json');
    echo json_encode($request);
    exit;
  }
  else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error: Specified logs do not exist!']);
    exit;
  } 
}
// Get the last ticket in the ticket database
else if (isset($_GET['last'])) {
  $last = getLast();
  if ($last !== null) {
    echo json_encode($last);
    exit;
  }
  else {
    echo json_encode(['error' => 'Error: Last ticket not found!']);
    exit;
  } 
}
// Get user information from a given gm_id
else if (isset($_GET['user'])) {
  $gm_id = trim($_GET['user']);
  $user = getUser($gm_id);
  if ($user !== null) {
        header('Content-Type: application/json');
    echo json_encode($user);
    exit;
  }
  else {
        header('Content-Type: application/json');
    echo json_encode(['error' => 'Error: User from gm id not found!']);
    exit;
  } 
}
// Get tickets for global queue
//else if (isset($_GET)) {
else if ($_SERVER['REQUEST_METHOD'] == 'GET'){
  $all = getAll();
  if ($all !== null) {
    header('Content-Type: application/json');
    echo json_encode($all);
    //echo json_encode(['error' => 'Error: Couldnt get all tickets!']);
    exit;
  }
  else {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'No free tickets in the database!']);
    exit;
  }
}
else {
  header('Content-Type: application/json');
  echo json_encode(['error' => 'Error: Invalid request received!']);
  exit;
}
  //header("location: /tickets.html");
?>
