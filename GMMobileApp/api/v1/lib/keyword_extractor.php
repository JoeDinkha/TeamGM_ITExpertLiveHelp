<?php
function extractCommonWords($string){
  $stopWords = array('consistent','only','your','after','i','a','about','an','and','are','as','at','be','by','com','de','en','for','from','how','in','is','it','la','of','on','or','that','the','this','to','was','what','when','where','who','will','with','und','the','www');
           
  //$string = preg_replace('/ss+/i', '', $string);
  $string = trim($string); // trim the string
  $string = preg_replace('/[^a-zA-Z0-9 -]/', '', $string); // only take alphanumerical characters, but keep the spaces and dashes too
  $string = strtolower($string); // make it lowercase
                       
  preg_match_all('/\b.*?\b/i', $string, $matchWords);
  $matchWords = $matchWords[0];
                                
  foreach ( $matchWords as $key=>$item ) {
    if ( $item == '' || in_array(strtolower($item), $stopWords) || strlen($item) <= 3 ) {
      unset($matchWords[$key]);
    }
  }   
  $wordCountArr = array();
  if ( is_array($matchWords) ) {
    foreach ( $matchWords as $key => $val ) {
      $val = strtolower($val);
      if ( isset($wordCountArr[$val]) ) {
        $wordCountArr[$val]++;
      } else {
        $wordCountArr[$val] = 1;
      }
    }
  }
  arsort($wordCountArr);
  $wordCountArr = array_slice($wordCountArr, 0, 10);
  return $wordCountArr;
}
 
$text = file_get_contents($argv[2]);
$words = extractCommonWords($text);
$keys = json_decode(file_get_contents('keys.json'), true);
//echo implode(',', array_keys($words));
foreach ($words as $key => $value) {
  if (array_key_exists($key, $keys)) {
    array_push($keys[$key], $argv[1]);
  }
  else {
    $keys[$key] = array($argv[1]);
  }
}
file_put_contents("keys.json",json_encode($keys));
?>
