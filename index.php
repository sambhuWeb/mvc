<?php

//use the auto loader
require 'config/path.inc.php';
require 'config/database.php';
require 'libs/function.php';

function __autoload($class){
  require "libs/$class.php";
}

echo "<pre>".print_r(get_class_methods('Browsercap'), true)."</pre>";

$app = new Bootstrap();