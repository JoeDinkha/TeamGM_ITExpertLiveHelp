<?php
/**
* \file /api/v1/lib/cls/Ticket.php
*
* \author Brian Hart
*/

/**
* \brief Class that implements a standard ticket
*/
class Ticket implements JsonSerializable {
  private $interaction_id; ///< Interaction id for a ticket
  private $category;       ///< Specific ticket category
  private $issue;          ///< Issue had (short title)
  private $status;         ///< Status of where ticket is currently in system
  private $desc;           ///< Description of problem had by user
  private $affected_service; ///< Service being affected by the problem
  private $gm_id;          ///< Identifier of user with the problem
  private $admin_gm_id;    ///< Identifier of SD employee working ticket
  private $notify_type;    ///< Notification type desired by user (call, etc)
  private $phase;          ///< Phase of the ticket in the system
  private $last_update_date; ///< Last time any ticket field was updated
  private $creation_date;  ///< Date of ticket creation
  private $feedback;       ///< Flag for whether or not feedback has been left for ticket
  private $flag;           ///< Flag for whether or not ticket has been changed
  private $image;          ///< URL of (optional) image from the user
  private $notes;          ///< Admin notes about the ticket
  private $request;        ///< String containing the additional information asked by admin
  
  /** \brief Constructor for a ticket
  * \param row A key/value array containing all pieces of a ticket
  */
  public function __construct($row) {
    $this->interaction_id = $row['interaction_id'];
    $this->category = $row['category'];
    $this->issue = $row['issue'];
    $this->status = $row['status'];
    $this->desc = $row['description'];
    $this->affected_service = $row['affected_service'];
    $this->gm_id = $row['gm_id'];//added 9/24
    $this->admin_gm_id = $row['admin_gm_id'];
    $this->notify_type = $row['notify_type'];
    $this->phase = $row['phase'];
    $this->last_update_date = $row['last_update_date'];
    $this->creation_date = $row['creation_date'];
    $this->feedback = $row['feedback'];
    //$this->notes = $row['notes'];
    $this->flag = $row['dirty'];
    if ($row['image'] === null) {
      $this->image = '';
    }
    else {
      $this->image = $row['image'];
    }

    if ($row['notes'] === null) {
      $this->notes = '';
    }
    else {
      $this->notes = $row['notes'];
    }
    
    if ($row['request'] === null) {
      $this->request = '';
    }
    else {
      $this->request = $row['request'];
    }
    
  }

  /** \brief Configures json encoding for an individual ticket
  * \return Proper json to be sent
  */
  public function jsonSerialize() {
    return ['interaction_id' => $this->interaction_id, 
 	'category' => $this->category,
	'issue' => $this->issue,
	'status' => $this->status,
	'description' => $this->desc,
	'affected_service' => $this->affected_service,
	'admin_gm_id' => $this->admin_gm_id,
	'gm_id' => $this->gm_id,
	'notify_type' => $this->notify_type,
	'phase' => $this->phase,
	'last_update_date' => $this->last_update_date,
	'creation_date' => $this->creation_date,
        'feedback' => $this->feedback,
	'notes' => $this->notes,
        'image' => $this->image,
        'request' => $this->request,
	'dirty' => $this->flag];
  }
}
?>
