<?php
class Model {
  function __construct() {
    //Database connection can go here    
    $this->db = new DBMC(new AppConfig());
    //$this->db->connect('mvc');
  }
}