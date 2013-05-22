<?php

class Login extends Controller{
  function __construct() {
    parent::__construct();
    //echo "This is index page [controller] <br />";    
  }

  public function index(){
    $this->view->render('commonInc/header');
    $this->view->render('login/index');
    $this->view->render('commonInc/footer');
  }

  public function run(){
    $this->model->run();
  }
}

