<?php

class Login_Model extends Model {  
  public function __construct() {
    parent::__construct();
    $this->db->connect('mvc');   
  }

  public function run(){
    $sql = "SELECT id FROM users WHERE login=:login AND password = md5(:password)";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':login', $_POST['login'], PDO::PARAM_STR);
    $stmt->bindParam(':password', $_POST['password'], PDO::PARAM_STR);
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0){//check if returns row
      Session::init();
      Session::set('loggedIn', true);
      header('location:'._CURRENT_DOMAIN.DS.'dashboard');
    } else {
      header('location:'._CURRENT_DOMAIN.DS.'login');
    }
    echo "<pre>".print_r($rows, true)."</pre>";
  }
}