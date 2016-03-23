<?php
/**
* \file /api/v1/lib/cls/Feedback.php
*
* \author Brian Hart
*/

/**
* \brief Class that implements standard feedback
*/
class Feedback implements JsonSerializable {
  private $interaction_id; ///< Interaction id for feedback
  //private $gm_id;          ///< Identifier of user submitting feedback
  private $admin_gm_id;    ///< Identifier of SD employee receiving feedback
  private $stars;          ///< How well on a scale of 1 to 5 the SD employee did
  private $comments;       ///< Any additional comments the user may have for the SD employee
  
  /** \brief Constructor for feedback
  * \param row A key/value array containing all pieces required to submit feedback
  */
  public function __construct($row) {
    $this->interaction_id = $row['interaction_id'];
    //$this->gm_id = $row['gm_id'];
    $this->admin_gm_id = $row['admin_gm_id'];
    $this->stars = $row['stars'];
    $this->comments = $row['comments'];
  }

  /** \brief Configures json encoding for feedback
  * \return Proper json to be sent
  */
  public function jsonSerialize() {
    return ['interaction_id' => $this->interaction_id, 
	//'gm_id' => $this->gm_id,
	'admin_gm_id' => $this->admin_gm_id,
	'stars' => $this->stars,
	'comments' => $this->comments];
  }
}
?>
