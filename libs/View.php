<?php

class View {
  function __construct() {
    //echo "View layer is loaded <br />";
  }

  public function render($name){
    require _APP_VIEW.$name.'.php';
  }

    public function renderMobile($name){
	  require _APP_VIEW.$name.'.php';
  }
}