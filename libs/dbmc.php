<?php
/*include '../config/path.inc.php';
include 'AppConfig.php';
include 'startup.php';
Startup::setServerDetails("../");*/

class DBMC {
  private $_appConfig, $_oPDO;

  /**
   * Inatialize DBMC object with AppConfig object
   * @param AppConfig $appConfig
   */
  public function __construct(AppConfig $appConfig) {
    if ($appConfig instanceof AppConfig) {
      $this->_appConfig = $appConfig;
    } else {
      throw new Exception("Invalid AppConfig object");
    }
  }

  public function __destruct() {
    $this->_oPDO = null;
    unset($this->_oPDO);
  }

  public function beginTransaction() {
    return $this->_oPDO->beginTransaction();
  }

  public function close() {
    $this->_oPDO = null;
    unset($this->_oPDO);
  }

  public function commit() {
    return $this->_oPDO->commit();
  }

  public function connect($dbName) {
    $aDBC = $this->_appConfig->getDbDetails($dbName."_db");
    //print_r($aDBC);
    try {
      $this->_oPDO = new PDO(
              'mysql:host='.$aDBC['dsn'].';dbname='.$aDBC['database'].';',
              $aDBC['username'],
              $aDBC['password'],
              //Persistent conn are not closed at the end of the script but are cached and re-used when another script request a connection using the same credentials
              //Persistent connection cache alllow to avoid the over of establishing a new conn and thus result in faster app
              array(PDO::ATTR_PERSISTENT => true) //Should I enable persisten connection
      );
      $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $pe) {
      die ('PDO Exception '.$pe->getMessage());
    }
  }

  public function bufferedMode() {
    return $this->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
  }

  public function errorCode() {
    return $this->_oPDO->errorCode();
  }

  public function errorInfo() {
    return $this->_oPDO->errorInfo();
  }

  public function exec($statement) {
    return $this->_oPDO->exec($statement);
  }

  public function getAttribute($attribute) {
    return $this->_oPDO->getAttribute($attribute);
  }

  public function getAvailableDrivers() {
    return $this->_oPDO->getAvailableDrivers();
  }

  public function getFoundRows() {
    return $this->query('SELECT FOUND_ROWS()')->fetchColumn();
  }

  public function rowsFound() {
    return $this->query('SELECT FOUND_ROWS()')->fetchColumn() > 0 ? true : false;
  }

  public function lastInsertId($name=null) {
    return $this->_oPDO->lastInsertId($name);
  }

  public function prepare($statement) {
    return $this->_oPDO->prepare($statement);
  }

  public function query($statement) {
    return $this->_oPDO->query($statement);
  }

  public function quote($string) {
    return $this->_oPDO->quote($string, $parameter_type);
  }

  public function rollBack() {
    return $this->_oPDO->rollBack();
  }

  public function setAttribute($attribute, $value) {
    return $this->_oPDO->setAttribute($attribute, $value);
  }
}

/*$db = new DBMC(new AppConfig());
$db->connect("mvc");*/