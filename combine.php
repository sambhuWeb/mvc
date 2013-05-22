<?php
require 'config/path.inc.php';
require_once 'libs/startup.php';
Startup::setServerDetails();
//define('DS', '/');
//define('OPDS', '\\');//Operating System (windows|Directory|) FileSystem Directory Seperator
//define('PHPEX', substr(strrchr(__FILE__, '.'), 1));
define('_COMBINE_FILE_ROOT', dirname(__FILE__)); //Root location of file combine.php


/*echo "<br>";
echo "Combine.php File Roots: "._COMBINE_FILE_ROOT;
echo "<br />";
echo _COMBINE_FILE_ROOT . _OPDS . 'libs' . _OPDS. 'CombineRender.' . PHPEX;
echo "<pre>".print_r($_SERVER, true)."</pre>";*/
require_once (_COMBINE_FILE_ROOT . _OPDS . 'libs' . _OPDS. 'CombineRender.' . PHPEX);
?>
