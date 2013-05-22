<?php
class Startup {
  /**
   * file calling this method should provide the directory level
   * For e.g Accessing from libs/File
   * setServerDetails("../");
   * @return <type>
   */
  public static function setServerDetails($dirLevel = null) {
    $preDirExt = (isset($dirLevel))?$dirLevel:"";//For e.g if $dirLevel = "../"
    $sFile = $preDirExt._CONFIG_DIR . 'servers.ini.' . PHPEX;

    if (file_exists($sFile)) {
      $aConfig = parse_ini_file($sFile, true);
    } else {
      trigger_error('Server config file missing', E_USER_ERROR);
    }
    //echo "<pre>".print_r($aConfig, true)."</pre>";
    define('_SERVER_NAME', $aConfig['server']['name']);
    define('_SERVER_TYPE', $aConfig['server']['type']);
    define('_OPDS', $aConfig['server']['OPDS']);

    return true;
  }
}