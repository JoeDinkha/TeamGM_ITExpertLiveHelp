<?php
/**
* \file /api/v1/lib/cls/Article.php
*
* \author Brian Hart
*/

/**
* \brief Class that implements a standard article
*/
class Article implements JsonSerializable {
  private $filename;  ///< The filename for the article (given in url form)
  private $header;    ///< Header for the file, essentially the title
  private $desc;      ///< Description of file, short summary of contents
  private $a_type;    ///< Type of file (either an article or a wizard)

  /** \brief Constructor for an article
  * \param row A key/value array containing all pieces of an article
  */
  public function __construct($row) {
    $this->filename = $row['filename'];
    $this->header = $row['header'][0];
    $this->desc = $row['description'][0];
    $this->a_type = $row['type'];
  }

  /** \brief Configures json encoding for an article
  * \return Proper json to be sent
  */
  public function getArticleType() {
    return $this->a_type;
  }

  /** \brief Configures json encoding for an article
  * \return Proper json to be sent
  */
  public function jsonSerialize() {
    return ['filename' => $this->filename, 
 	'header' => $this->header,
	'description' => $this->desc,
	'type' => $this->a_type];
  }
}
?>
