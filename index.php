<?php
//use the auto loader
require 'config/path.inc.php';
require 'config/database.php';
require_once 'libs/Browsercap.php';
require 'libs/function.php';
require_once 'libs/startup.php';
require 'libs/Bootstrap.php';
//require _FILE_ROOT. DS. 'config'. DS. 'path.inc.' . PHPEX;
require 'libs/Controller.php';
require 'libs/View.php';
require 'libs/Model.php';

//library
//require 'libs/Database.php';
require 'libs/AppConfig.php';
require 'libs/dbmc.php';
require 'libs/Session.php';

$app = new Bootstrap();