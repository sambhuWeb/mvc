<?php
class Controller {
  public function __construct() {
    $this->view = new View();
    $this->loadCss();
    $this->loadJs();
  }

  /**
   * Load default css files if not overridden by sub class extending this method
   *
   * If this method is overridded by sub class and array param is passed then
   * custom css files is loaded and store to jsFilesLink var on the ELSE section
   * for e.g., in a sub class custom CSS files can be constructed using
   * parent::loadJs(array("dashboard", "widget")); //passing css folder name
   * 
   * @param <type> $customFiles Array of custom css files to be loaded
   */
  public function loadCss($customFiles = ""){
    if (!isset($customFiles) || !is_array($customFiles) || empty($customFiles)) {
      $this->view->cssFilesLink = GenerateJsCssLink::combineCSSFiles();
    } else {
      $this->view->cssFilesLink = GenerateJsCssLink::combineCSSFiles($customFiles);
    }
  }

  /**
   * Load default js files if not overridden by sub class extending this method
   *
   * If this method is overridded by sub class and array param is passed then
   * custom js files is loaded and store to jsFilesLink var on the ELSE section
   * for e.g., in a sub class custom JS files can be constructed using
   * parent::loadJs(array("alert", "rotate")); //passing js folder name 
   *
   * @param <type> $customFiles  Array of custom js files
   */
  public function loadJs($customFiles = ""){
    if (!isset($customFiles) || !is_array($customFiles) || empty($customFiles)) {
      $this->view->jsFilesLink = GenerateJsCssLink::combineJSFiles();//load default js
    } else {
      $this->view->jsFilesLink = GenerateJsCssLink::combineJSFiles($customFiles);//process custom js files
    }
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