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
    parent::loadJs(array("jquery", "dashboard"));
    $this->view->render('commonInc/header');
    $this->view->render('dashboard/index');
    $this->view->render('commonInc/footer');
  }
  
  public function logout(){
    Session::destroy();
    header('location:'._CURRENT_DOMAIN.DS.'login');
    exit();
  }

  function xhrInsert(){//xml http request
    $this->model->xhrInsert();
  }

  public function xhrGetListings(){
    $this->model->xhrGetListings();
  }

  public function xhrDeleteListing(){
    $this->model->xhrDeleteListing();
  }
}

