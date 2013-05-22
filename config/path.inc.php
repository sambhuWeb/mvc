<?php

//define('OPDS', '\\');//Operating System (windows|Directory|) FileSystem Directory Seperator
/**
 * OPDS taken care of in config/servers.ini.php
 * REMEMBER to add config file in deploy where
 * type = production
 * OPDS = /
 */

if(!defined('DS'))
  {define('DS', '/');}//web DS compatible for for linux and windows
if(!defined('PHPEX'))
  {define('PHPEX', substr(strrchr(__FILE__, '.'), 1));}
if(!defined('_FILE_ROOT'))
  {define('_FILE_ROOT', dirname(dirname(__FILE__)));}//Root location of file
if(!defined('_APP_CONTROLLER'))
  {define('_APP_CONTROLLER', 'app/controllers/');}
if(!defined('_APP_MODEL'))
  {define('_APP_MODEL', 'app/models/');}
if(!defined('_APP_VIEW'))
  {define('_APP_VIEW', 'app/views/');}
if(!defined('_LIBS'))
  {define('_LIBS', 'libs/');}
if(!defined('_CURRENT_DOMAIN'))
  {define('_CURRENT_DOMAIN', 'http://'.$_SERVER['HTTP_HOST']);}
if(!defined('_CONFIG_DIR'))
  {define('_CONFIG_DIR', 'config'.DS);}
    //{define('_CONFIG_DIR', _FILE_ROOT.'\\config\\');}