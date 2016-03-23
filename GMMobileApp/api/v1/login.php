<?php
  require("lib/site.inc.php");
  require("lib/cls/User.php");

function login($email, $password) {
$pdo = new PDO('mysql:host=localhost;dbname=gm', 'root', 'GMteammsu23679');
  $sql = <<<SQL
SELECT * from users
  where email=?
SQL;

  $statement = $pdo->prepare($sql);

  $statement->execute(array($email));
  if($statement->rowCount() === 0) {
    return null;

  }
  $row = $statement->fetch(PDO::FETCH_ASSOC);

  if($row['password'] === $password) {
	return new User($row);
  }
  return null;

}

if(isset($_POST['email']) && isset($_POST['password'])) {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  $user = login($email, $password);
  if($user !== null) {
    $_SESSION['user'] = $user;
    header("location: /");
    exit;
  }
} else {
$raw_json = file_get_contents('php://input');
$json = json_decode($raw_json, true);

if($json !== null) {
  $user = login($json["email"], $json["password"]);
  if($user !== null) {
    header('Content-Type: application/json');
    echo json_encode($user);
    exit;
  } else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Login failed']);
    exit;
}
}
header("location: /login.html");
}

?>
