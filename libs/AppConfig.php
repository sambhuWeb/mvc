<?php
class AppConfig {
  private $_aConfig;
  public function  __construct() {
    if ($this->_aConfig == null){
      $sFile = _CONFIG_DIR. 'dbconfig_' . _SERVER_TYPE. '.ini.' . PHPEX;
      //echo "<br />File: ".$sFile."<br>";
      if (file_exists($sFile)){
        $this->_aConfig = parse_ini_file($sFile, true);
      } else {
        throw new Exception('Config File Missing!!!!');
      }
    }
  }

  /**
   *
   * @param <type> $dbName Name of database on config file for e.g. [mvc] with _db extension
   * @return <type> database username, dns etc specified on config file if it exist
   */
  public function getDbDetails($dbName){
    if (isset($this->_aConfig[$dbName])){
      return $this->_aConfig[$dbName];
    } else {
      throw new Exception('dbName not Found!');
    }
  }
}