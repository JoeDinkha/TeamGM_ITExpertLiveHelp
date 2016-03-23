<?php
/**
* \file /api/v1/help_data/index.php
*
* \author Brian Hart
*/

  require("../lib/cls/Article.php");
  require("../lib/cls/Requests.php");

/** \brief Gets the characters between quotations and formats them 
*          properly for the Solr request (removes from original string,
           passed back in a seperate formatted string)
* \param string The string to gather quotation-grouped strings
* \returns text A string containing all characters between quotations
*          (multiple different quotations works as well)
*/

function getInbetween(&$string) {
  $matches = array();
  preg_match_all('/"(.*?)"/', $string, $matches);
  $string = preg_replace('/".*?"/', '', $string);
  $text = "";
  $result = $matches[1];
  foreach ($result as $key => &$str) {
    $str = preg_replace('/_/', '+', $str);
    $text .= '+%22' . $str . '%22';
  }
  return $text;
}

/** \brief Creates a properly formatted string for the Solr request
*          (currently adds fuzziness to all words, allows for enclosed
*          quotations, and allows for wildcard * character)
* \param string The string given to query Solr
* \returns query Properly formatted string for Solr request
*/
function createQuery($string) {
  require("stopwords.php");
  
  $exact = '';
  $exact = getInbetween($string);

  // For all underscores, all non-alphanumeric characters, and all words that are a stop word...
  $patterns = array('/_+/', '/[^\*A-Za-z0-9 ]/', '/\*+/');
  // ... replace with a single space, null, and null (respectively)
  $replace = array(' ', '', '*');
  $query = preg_replace($patterns, $replace, $string);
  $query = trim($query);

  if ($query !== '') {
    $query_array = explode(" ", $query);
    $query_array = array_diff($query_array, $stopwords);
    foreach ($query_array as $key => &$str) {
      if (substr($str, -1) !== '*' && substr($str, -1) !== '~') {
        $str .= '~';
      }
      if (substr($str, 0, 1) === '*') {
        $str = substr($str, 1);
      }
    } 
    $query = implode("+", $query_array);
  }

  $query .= $exact;
  if ($query === '') {
    return null;
  }
  else {
    return $query;
  }
}

/** \brief Gets relevant articles from database based on keys
* \param keys The keys to match against the keys in the database
* \returns Top 4 article matches, each contains the url to the file, a header 
*          and a short description (sorted wizards first, articles second)
*/
function searchArticles($keys) {
  $raw_json = file_get_contents("http://localhost:8983/solr/help_data/query?q=" . $keys);
  $json = json_decode($raw_json, true);
  $response = $json['response'];
  $articles = null;
  if ($response['numFound'] > 0) {
    $docs = $response['docs'];

    $wizards = array();
    $regulars = array();
    foreach($docs as $doc) {
      $article = new Article($doc);
      if ($article->getArticleType() === "wizard") {
        $wizards[] = $article;
      }
      else {
        $regulars[] = $article;
      }
    }
    
    $sorted = $wizards;
    foreach($regulars as $regular) {
      $sorted[] = $regular;
    }

    $articles = new Requests($sorted, Requests::Articles);
  }

  return $articles;
}

// .../api/v1/help_data?search=blah_blah
if (isset($_GET['search'])) {
  // Format string for search function
  $raw_string = trim(urldecode($_GET['search']));
  $query = createQuery($raw_string);
  if($query !== null) {
    $result = searchArticles($query);
    if($result !== null) {
      header('Content-Type: application/json');
      echo json_encode($result);
      exit;
    } else {
      header('Content-Type: application/json');
      echo json_encode(['error' => 'No relevant articles found!']);
      exit;
    }
  }
  else {
    echo json_encode(['error' => 'Input not descriptive enough, no relevant articles found!']);
  }
}

?>
