<?php
/*
Based on the php proxy found at http://help.arcgis.com/en/webapi/javascript/arcgis/help/jshelp_start.htm
*/
error_reporting(0);
require_once("../../../wp-config.php");
global $linkID;
$linkID = $_GET["link_id"];
global $qs;
$qsA = explode("qs=", $_SERVER["QUERY_STRING"]);
$qs = $qsA[1];
$clean = $_GET["clean"]=="0"?false:true;
$tBase = "/links_2proxy/".$linkID."/".$_GET["clean"]."/";
$pIb=explode($tBase, $_SERVER["REQUEST_URI"]);
$pI=$pIb[1];
$lnkChk = get_bookmark( intval($linkID) );
$targetUrl=$lnkChk->link_url;
$mustMatch = true;
function is_url_allowed() {
global $linkID;
$defaults = array('fields'=>'names');
$lnkCats = wp_get_object_terms(intval($linkID), 'link_category', $defaults);    
  $isOk = false;
    if ( $lnkCats && ! is_wp_error( $lnkCats ) ){
        foreach($lnkCats as $lnkCat){
            if ($lnkCat=="allow-proxy") {
              $isOk = true; // array index that matched
            }
        }        
    }
return $isOk;
}
  
  // check if the curl extension is loaded
  if (!extension_loaded("curl")) {
    header('Status: 500', true, 500);
    echo 'cURL extension for PHP is not loaded! <br/> Add the following lines to your php.ini file: <br/> extension_dir = &quot;&lt;your-php-install-location&gt;/ext&quot; <br/> extension = php_curl.dll';
    return;
  }
  
  if (!$targetUrl) {
    header('Status: 400', true, 400); // Bad Request
    echo 'Bad URL.';
    return;
  }
  
  $parts = preg_split("/\?/", $targetUrl);
  $targetPath = $parts[0];
  
  // check if the request URL matches any of the allowed URLs
  if ($mustMatch) {
    $pos = is_url_allowed();
    if ($pos === false) {
      header('Status: 403', true, 403); // Forbidden
      echo 'Bad URL';
      return;
    }
  }
  

  
  // open the curl session
  $session = curl_init();
  
  // set the appropriate options for this request
  $options = array(
    CURLOPT_URL => $targetUrl.$pI,
    CURLOPT_HEADER => false,
    CURLOPT_HTTPHEADER => array(
      'Content-Type: ' . $_SERVER['CONTENT_TYPE'],
      'Referer: ' . $_SERVER['HTTP_REFERER']
    ),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true
  );
  
  // put the POST data in the request body
  $postData = file_get_contents("php://input");
  if (strlen($postData) > 0) {
    $options[CURLOPT_POST] = true;
    $options[CURLOPT_POSTFIELDS] = $postData;
  }
  curl_setopt_array($session, $options);
  
  // make the call
  $response = curl_exec($session);
  $code = curl_getinfo($session, CURLINFO_HTTP_CODE);
  $type = curl_getinfo($session, CURLINFO_CONTENT_TYPE);
  curl_close($session);
  
  // set the proper Content-Type
  header("Status: ".$code, true, $code);
  header("Content-Type: ".$type);
  if($clean){
  echo preg_replace('/[^(\x20-\x7F)]*/','', $response);
  }else{
  echo $response;
  }
?>