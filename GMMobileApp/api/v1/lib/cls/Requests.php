<?php
/**
* \file /api/v1/lib/cls/Requests.php
*
* \author Brian Hart
*/

/**
* \brief Class that implements an array of tickets and json
*/
class Requests implements JsonSerializable {
  /// Array for object storage
  private $requests = array();
  /// Indicator for which type of object contained
  /// 0 for tickets, 
  private $obj_type;

  // Predefined possible object types
  const Tickets = 0;
  const Logs = 1;
  const Articles = 2;
  const Feedback = 3;

  /** \brief Constructor for the requests class
  * \param tickets The tickets to store 
  */
  public function __construct($tickets, $type) {
  //public function __construct($tickets) {
    $this->requests = $tickets;
    $this->obj_type = $type;
    //$this->obj_type = 0;
  }

  /** \brief Gets the tickets stored in array
  * \return The tickets held in requests array
  */
  public function getRequests() {
    return $this->requests;
  }

  /** \brief Configures json encoding for the Requests class
  * \return Proper json to be sent
  */
  public function jsonSerialize() {
    if ($this->obj_type == Requests::Tickets) {
      return ['tickets' => $this->requests];
    }
    if ($this->obj_type == Requests::Logs) {
      return ['logs' => $this->requests];
    }
    if ($this->obj_type == Requests::Articles) {
      return ['articles' => $this->requests];
    }
    if ($this->obj_type == Requests::Feedback) {
      return ['feedback' => $this->requests];
    }
  }
}
?>
