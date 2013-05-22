<?php

class Login_Model extends Model {  
  public function __construct() {
    parent::__construct();
    $this->db->connect('mvc');//connect to mvc database
    //echo md5('sambhu');
  }

  public function run(){
    //$login = $_POST['login'];
    //$password = $_POST['password'];
    $sql = "SELECT id FROM users WHERE login=:login AND password = md5(:password)";
    $stmt = $this->db->prepare($sql);//send query to server
    $stmt->bindParam(':login', $_POST['login'], PDO::PARAM_STR);
    $stmt->bindParam(':password', $_POST['password'], PDO::PARAM_STR);
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0){//check if returns row
      Session::init();
      Session::set('loggedIn', true);
      header('location: ../dashboard');
    } else {
      header('location: ../login');
    }
    echo "<pre>".print_r($rows, true)."</pre>";
  }
}