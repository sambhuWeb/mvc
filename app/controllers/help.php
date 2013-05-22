<?php

class Help extends Controller{
  function __construct() {
    parent::__construct();    
  }

  public function index(){
    $this->view->render('commonInc/header');
    $this->view->render('help/index');
    $this->view->render('commonInc/footer');
  }

  public function other($arg = false) {
    /*echo "We are inside other method of help [controller] <br />";
    if ($arg != false){
      echo "Argument: ". $arg."<br />";
    }*/
    
    require 'app/models/help_model.php';
    $model = new Help_Model();
    $this->view->add = $model->add();

  }
}