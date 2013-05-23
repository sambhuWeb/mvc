<?php

class Index extends Controller {
  function __construct() {
    parent::__construct();
  }

  public function index() {
    parent::loadCss(array("bootstrap", "bootstrap-responsive", "default"));    
    parent::loadJs();
    
    $this->view->msg = 'Message passed from controller to view <br />';
    $this->view->render('commonInc/header');
    $this->view->render('index/index');
    $this->view->render('commonInc/footer');
  }

  public function details() {
    $this->view->msg = "details page";

    $this->view->render('commonInc/header');
    $this->view->render('index/details');
    $this->view->render('commonInc/footer');
  }
}

