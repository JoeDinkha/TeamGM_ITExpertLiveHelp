<?php

class UserData implements JsonSerializable {
  private $email;
  private $gm_id;
  private $first_name;
  private $last_name;
  private $mobile_phone;
  private $work_phone;
  private $presence;
  public $schedule;

  public function __construct($row) {
    $this->email = $row['email'];
    $this->gm_id = $row['gm_id'];
    $this->first_name = $row['first_name'];
    $this->last_name = $row['last_name'];
    $this->mobile_phone = $row['mobile_phone'];
    $this->work_phone = $row['work_phone'];
    $this->presence = $row['presence'];
  }

  public function getName() {
    return $this->first_name . ' ' . $this->last_name;
  }

  public function getEmail() {
    return $this->email;
  }

  public function jsonSerialize() {
    return ['email' => $this->email,
        'gm_id' => $this->gm_id, 
 	'first_name' => $this->first_name,
	'last_name' => $this->last_name,
	'mobile_phone' => $this->mobile_phone,
	'work_phone' => $this->work_phone,
	'presence' => $this->presence,
	'schedule' => $this->schedule];
  }
}
?>
