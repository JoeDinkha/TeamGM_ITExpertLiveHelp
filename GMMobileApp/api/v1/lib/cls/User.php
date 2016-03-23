<?php

class User implements JsonSerializable {
  private $admin;
  private $email;
  private $first_name;
  private $last_name;
  private $password;
  private $gm_id;
  private $mobile_phone;
  private $work_phone;
  private $device_token;

  public function __construct($row) {
    $this->email = $row['email'];
    $this->password = $row['password'];
    $this->admin = $row['admin'];
    $this->first_name = $row['first_name'];
    $this->last_name = $row['last_name'];
    $this->gm_id = $row['gm_id'];
    $this->mobile_phone = $row['mobile_phone'];
    $this->work_phone = $row['work_phone'];
    $this->device_token = $row['device_token'];
  }

  public function getName() {
    return $this->first_name . ' ' . $this->last_name;
  }

  public function getEmail() {
    return $this->email;
  }

  public function jsonSerialize() {
    return ['email' => $this->email, 
 	'first_name' => $this->first_name,
	'last_name' => $this->last_name,
	'password' => $this->password,
	'admin' => $this->admin,
	'gm_id' => $this->gm_id,
	'mobile_phone' => $this->mobile_phone,
	'work_phone' => $this->work_phone,
        'device_token' => $this->device_token];
  }
}
?>
