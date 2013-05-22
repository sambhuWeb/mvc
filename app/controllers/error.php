<?php

class Error extends Controller {
  function __construct() {
    parent::__construct();
    //echo "Page doesen't exist <br />";

    //assign value to a view
    //$this->view->msg = 'Message passed from controller to view <br />';
    //$this->view->render('error/index');
  }

  public function index(){
    $this->view->msg = 'Message passed from controller to view error class<br />';

    $this->view->render('commonInc/header');
    $this->view->render('error/index');
    $this->view->render('commonInc/footer');
  }
}