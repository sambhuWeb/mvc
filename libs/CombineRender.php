<?php
/**
 * Objective of CombineRender to reduce size of file:
 * 1. Combine multiple JS or CSS- [Address DNS look up]
 * 2. gZip - [compress the big file]
 * 3. cache - [Cache the file]
 *
 *
 * https://github.com/Web3r/Taxicode/blob/master/lib/render/CombineRender.class.php
 * http://rakaz.nl/code/combine
 * http://code.google.com/p/gzipit/
 *
 */

//$_GET['type'] = "css";//"javascript";
//$_GET['files'] = "default|testcss/";//"test|test2/";

$cache = true;
$cachedir = _COMBINE_FILE_ROOT . _OPDS . 'tmp' . _OPDS . 'cache' . _OPDS;//C:\xampp\htdocs\www\webProduction\mvc\tmp\cache
$cssdir = _COMBINE_FILE_ROOT . _OPDS . 'public' . _OPDS . 'css' . _OPDS;
$jsdir = _COMBINE_FILE_ROOT . _OPDS . 'public' . _OPDS . 'js' . _OPDS;
$cachejs = 'js';
$cachecss = 'css';
$cachejscss = 'jscss';
$fileext = '';
$cachefolder = '';
$precontents = '';

if (isset($_GET['type'])) {
  //echo "<br />Get Type: ".$_GET['type']."<br>";
}
if (isset($_GET['files'])) {
  //echo "Get File: ".$_GET['files']."<br>";
}

//echo "<br /><br />";
/*
  $headers = apache_request_headers();

  foreach ($headers as $header => $value) {
    echo "$header: $value <br />\n";
}
*/
$aPackages = array(
        'public_js' => 'jquery|jqueryui',
        'public_css' => 'bscss|jqueryui'
);

//Determine the directory and type[js|css] we should use
switch($_GET['type']) {
  case 'css':
    $base = realpath($cssdir);
    $fileext = 'min.css';
    $cachefolder = $cachecss;
    $type = 'css';
    break;
  case 'javascript':
    $base = realpath($jsdir);
    $fileext = 'min.js';
    $cachefolder = $cachejs;
    $type = 'javascript';
    break;
  case 'javascriptcss'://css in a javasccript folder
    $base = realpath($jsdir);
    $fileext = 'css';
    $cachefolder = $cachejscss;
    $type = 'css';
    break;
  default:
    header("HTTP/1.0 503 Not Implemented");//Tells browser it wants fersh version of the resource; server tells browser not to cache the resource
    exit;
}

//echo "<pre>".print_r($_GET['files'], true)."</pre>";
$_GET['files'] = substr($_GET['files'], 0, strlen($_GET['files']) - 1);//substr($string, $start, length=0)
//echo "<pre>".print_r($_GET['files'], true)."</pre>";
$elements = explode('|', $_GET['files']);//bootstrapJS|defaultJS; elements(bootstrapJS, defaultJS)

//echo "<pre>".print_r($elements, true)."</pre>";
//echo "element[0]: ".$elements[0];

/*
 * If public_js value of $element array is present in the $aPackages then explode it and merge it with the $element array
 *
 * For e.g.
 * if element(public_js|bootstrap);
 *
 * then, $elements[0]:public_js is present in $aPackages
 * it will then explode the public_js(jquery, jqueryui) and merge with element array
 *
 * the final content of element will be
 * element(jquery|jqueryui|bootstrap)
*/
if (isset($aPackages[$elements[0]])) {
  $elements = array_merge($elements, explode('|', $aPackages[$elements[0]]));
  unset($elements[0]);
}

/*
 * returns an array in flip order - the key become values and values become keys in an array
*/
$elements = array_flip($elements);

//echo "<pre>".print_r($elements, true)."</pre>";

foreach ($elements as $element => $key) {
  if (substr($element, 0, 1) == '^') {
    unset($elements[substr($element, 1)], $elements[$element]);
  }
}

$elements = array_flip($elements);

//echo "Flip array back ?";
//echo "<pre>".print_r($elements, true)."</pre>";

$iElementsCount = count($elements);

//Determine last modification date of the files
$lastmodified = 0;
/*
 * Same Concept
 * $items = array(0 => 'cloths', 1 => 'shoes', 2 => 'tie');
 * while (list($key, $item) = each($items)){
 *   echo $key."<br />";
 *   echo $item."<br />";
 * }
 * foreach ($items as $key => $item){
 *   echo $key."<br />";
 *   echo $item."<br />";
 * }
*/
//While key and element present in each returned set of key and value from an array
while (list($key, $element) = each($elements)) {//each() returns current key and value pair from an array
  switch($_GET['type']) {
    case 'css':
      $path = _COMBINE_FILE_ROOT . _OPDS . 'public' . _OPDS . 'css' . _OPDS . $element . _OPDS. $element . '.' .$fileext;
      break;
    case 'javascript':
      $path = realpath($base . _OPDS. $element . _OPDS . $element . '.' . $fileext);
      break;
    case 'javascriptcss':
      $path = realpath($base . _OPDS . $element. _OPDS . 'style.'. $fileext);
      break;
    default:
      header("HTTP/1.0 503 Not Implemented");
      exit;
  }

  //check if type and file is same
  if (($type == 'javascript' && substr($path, -3) != '.js') || ($type == 'css' && substr($path, -4) != '.css')) {
    header("HTTP/1.0 403 Forbidden");
    exit;
  }

  //check file
  if (substr($path, 0, strlen($base)) != $base || !file_exists($path)) {//doesn't exist
    //echo "File doesn't exist: ".$path;
    unset($elements[$key]);
    $precontents .= 'alert("JS/CSS File Missing : ' . $element . '. \n File URI: ' . $path .'\n this file will not be cached until rectified.");';
    $cache = false;
  } else {
    //echo "File exist: ".$path;
    $lastmodified = max($lastmodified, filemtime($path));//check lastmodified vs file modified time and return highest value
    $hash = $lastmodified.'-'.md5($_GET['files']);//create unique hash
    //one of HTTP mechanism for web cache validation (saves bandwidth as server don't need to sent a response if content has not been changed; If content change new ETag will be assigned
    header("Etag: \"" .$hash."\"");
  }
}

//Check if the content has changed after this date and send a 304 if it hasn't
if (isset($_SERVER['HTTP_IF_NONE_MATCH'])
        && stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) == '"' . $hash . '"') {
  //if the req URL has not change since last accessed or cached then dont Send anything as no modification
  header("HTTP/1.0 304 not Modified");
  header('Content-Length: 0');
} else {//if hash different or page has been changed then
  //echo "<br>first time visit | file modified***<br>";
  //fist time visit or files modified
  if ($cache) {
    //Determine supported compression method
    $gZip = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gZip');//find first occurance of a string
    $deflate = strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate');

    //Determine compression method $gZip if present or $deflate else none
    //$encoding = $gZip ? 'gZip' : ($deflate ? 'deflate' : 'none');
    $encoding = $gZip ? 'gZip' : 'none';//disable deflate encoding and it has problem opeing page with Chrome 2nd time if opened with firefox first and Viceversa

    // Check for buggy versions of Internet Explorer
    if (!strstr($_SERVER['HTTP_USER_AGENT'], 'Opera') &&
            preg_match('/^Mozilla\/4\.0 \(compatible; MSIE ([0-9]\.[0-9])/i', $_SERVER['HTTP_USER_AGENT'], $matches)) {
      $version = floatval($matches[1]);

      if ($version < 6)
        $encoding = 'none';

      if ($version == 6 && !strstr($_SERVER['HTTP_USER_AGENT'], 'EV1'))
        $encoding = 'none';
    }

    //try the cache first to see if the combined files were already genereted
    $cachefile = 'cache-'.$hash.'.'.$type.($encoding != 'none' ? '.'.$encoding: '');

    //echo "Cache File: ".$cachefile."<br>";
    //echo "Cache Dir before if STMT: ".$cachedir . $cachefolder . _OPDS . $cachefile."<br>";

    if (file_exists($cachedir . _OPDS. $cachefolder . _OPDS . $cachefile)) {
      //echo "Cache Dir^^^^^^^^^^^^^^^^^^: ".$cachedir . $cachefolder . _OPDS . $cachefile."<br>";
      if ($fp = fopen($cachedir . $cachefolder . _OPDS . $cachefile, 'rb')) {//rb: Read Binary
        if ($encoding != 'none') {
          header ("Content-Encoding: " . $encoding);
        }

        header("Content-type: text/".$type);
        header("Content-Length: ".filesize($cachedir . _OPDS . $cachefolder . _OPDS . $cachefile));

        fpassthru($fp);//pass all remaining data to file pointer
        fclose($fp);
        exit;
      }
    } else {
      //echo "<br>File Path doesnt exist****<br>";
    }
  }


//Get contents of the files
  $contents = $precontents;
  //echo "Real Path: ".$base . _OPDS . $element . '.' . $fileext."<br><br>";
//rewind array's internal pointer to the first element and returns the value of the first array
  reset($elements);
//list() like array not really a function (, $element): it skip to 2nd one [Don't know yet why]
  while (list(, $element) = each($elements)) {
    switch ($_GET['type']) {
      case 'css':
        $path = realpath($base . _OPDS . $element . _OPDS. $element . '.' . $fileext);
        break;
      case 'javascript':
        $path = realpath($base . _OPDS. $element . _OPDS . $element . '.' . $fileext);
        break;
      case 'javascriptcss':
        $path = realpath($base . _OPDS . $element. _OPDS . 'style.'. $fileext);
        break;
      default:
        header("HTTP/1.0 503 Not Implemented");
        exit;
    }

    $contents .= file_get_contents($path)."\n";
  }

  //sent Content-type
  header("Content-Type: text/" . $type);

  if (isset($encoding) && $encoding != 'none') {
    //send compressed contents

    //IMPORTANT NOTE: Enable following two for compressing as js and css file don't seem to work when compressed
    //$contents = gzencode($contents, 9, $gzip ? FORCE_GZIP : FORCE_DEFLATE);
    //header("Content-Encoding: " . $encoding);
    header('Content-Length: ' . strlen($contents));
    //header('Expires: Wed, 1 Jan 2020 00:00:01 GMT'); //Enabling this require browser cache to be refreshed everytime a change has been made to JS file
    echo $contents;
  } else {
    //send regular contents with expiry header
    header('Content-Length: ' . strlen($contents));
    //header('Expires: Wed, 1 Jan 2020 00:00:01 GMT');
    echo $contents;
  }

  //echo "CAche Folder: ".$cachedir . _OPDS .$cachefolder . _OPDS. $cachefile;
  //Store cache
  if ($cache) {
    if ($fp = fopen($cachedir . _OPDS . $cachefolder . _OPDS. $cachefile, 'wb')) {
      fwrite($fp, $contents);
      fclose($fp);
    }
  }
}
?>
