<?php
/**
* \file /api/v1/image/index.php
*
* \author Brian Hart
*/

/** \brief Updates database ticket with stored image url
* \param picture_url Part of URL to the image
* \param upload_dir Directory in which the images are stored
* \param interaction_id Ticket identifier for updated image
* \returns Successful database update or unsuccessful
*/
function updatePicture($picture_url, $upload_dir, $interaction_id) {
  try {
  require("/var/database_config.php");
  $pdo = new PDO('mysql:host=' . $db_config['host'] . ';dbname=' . $db_config['dbname'], $db_config['username'], $db_config['password']);
  
  $sql_str = "SELECT image from tickets WHERE interaction_id = ?";
  $statement = $pdo->prepare($sql_str);
  $statement->execute(array($interaction_id));

  $picture = $statement->fetch(PDO::FETCH_ASSOC);
  if ($picture['image'] !== NULL) {
    $temp = explode("/", $picture['image']);
    $file = end($temp);
    $picture_path = $upload_dir . $file;
    if (file_exists($picture_path)) {
      if (unlink($picture_path)) {
        //return array('status' => 'File deleted');
      }
    }
  }

  $sql_str = "UPDATE tickets SET image = ? WHERE interaction_id = ?";
  $statement = $pdo->prepare($sql_str);
  $statement->execute(array($picture_url, $interaction_id));

  $logs = array();
  $logs[] = "Image added to ticket.";
  
  require("../log.php");
  writeLog($interaction_id, $logs);
  
  return array('status' => 'Successful image update');
  }
  catch (PDOException $e) {
  return array ('error' => 'Error updating the image in the database');
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $raw_json = file_get_contents('php://input');
  $json = json_decode($raw_json, true);
  if(isset($json['interaction_id'])) {
    $interaction_id = $json['interaction_id'];
    if(isset($json['image'])) {
      $image_bytes = $json['image'];
      $content = base64_decode($image_bytes);
      $image = imagecreatefromstring($content);
      if ($image === false) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Error: Could not decode the image!']);
        exit;
      }
      $upload_dir = '/var/www/gm/assets/pictures/';
      $upload_url = 'http://35.9.22.229:8080/assets/pictures/';
      // Generates unique filename for the image
      $filename = uniqid('image', true) . '.jpg';
      // Stores file on the server 
      if (imagejpeg($image, $upload_dir . $filename)) {
        $picture_url = $upload_url . $filename;
        $result = updatePicture($picture_url, $upload_dir, $interaction_id);
        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
      }
      else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Error: Could not store the image!']);
        exit;
      }
    }
    else {
      header('Content-Type: application/json');
      echo json_encode(['error' => 'Error: Image not found!']);
      exit;
    }

  }
  else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error: Interaction ID not found!']);
    exit;
  }
}
else {
  header('Content-Type: application/json');
  echo json_encode(['error' => 'Error: Invalid POST!']);
  exit;
}
?>
