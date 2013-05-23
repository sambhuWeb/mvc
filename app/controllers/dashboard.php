<?php

class Dashboard extends Controller{
  function __construct() {
    parent::__construct();
    Session::init();
    $logged = Session::get('loggedIn');
    if ($logged == false){
      Session::destroy();
      header('location:'._CURRENT_DOMAIN.DS.'login');
      exit;
    }
  }

  public function index(){
    $this->view->render('commonInc/header');
    $this->view->render('dashboard/index');
    $this->view->render('commonInc/footer');
  }
  
  public function logout(){
    Session::destroy();
    header('location:'._CURRENT_DOMAIN.DS.'login');
    exit();
  }
}

