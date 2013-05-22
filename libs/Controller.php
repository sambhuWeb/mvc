<?php
class Controller {
  function __construct() {
    //echo 'Main Controller <br />';
    $this->view = new View();

    
  }

  /**
   * Auto instantiate Model object base on the name provided on parameter
   * @param <type> $name Model object
   */
  public function loadModel($name){
    $path =  _APP_MODEL.$name.'_model.php';
    if (file_exists($path)) {
      require _APP_MODEL.$name.'_model.php';
      $modelName = $name.'_Model';
      $this->model = new $modelName();
    }
  }
}