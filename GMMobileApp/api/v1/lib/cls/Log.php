<?php
/**
* \file /api/v1/lib/cls/Log.php
*
* \author Brian Hart
*/

/**
* \brief Class that implements a standard log
*/
class Log implements JsonSerializable {
  private $interaction_id;  ///< The identifier for the ticket being logged
  private $date;            ///< The date/time the log is created
  private $desc;            ///< The log data (changes made, etc)

  /** \brief Constructor for a log
  * \param row A key/value array containing all pieces of a ticket 
  */
  public function __construct($row) {
    $this->interaction_id = $row['interaction_id'];
    $this->date = $row['date'];
    $this->desc = $row['description'];
  }

  /** \brief Configures json encoding for a log
  * \return Proper json to be sent
  */
  public function jsonSerialize() {
    return ['interaction_id' => $this->interaction_id, 
	'date' => $this->date,
	'description' => $this->desc];
  }
}
?>
