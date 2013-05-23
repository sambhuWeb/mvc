<?php

class Dashboard_Model extends Model {
  public function __construct() {
    parent::__construct();
    $this->db->connect('mvc'); 
  }

  public function xhrInsert(){
    //@todo: sanitize the text
    $text = $_POST['text'];
    
    $sql = "INSERT INTO data (text) VALUES (:text)";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':text', $text ,PDO::PARAM_STR);
    $stmt->execute();


    $data = array('text' => $text, 'id' => $this->db->lastInsertId());
    echo json_encode($data);
    //Above two line can also be written as below:
    //$stmt->execute(array(':text' => $text);
  }

  public function xhrGetListings(){
    $sql = "SELECT * from data";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($rows > 0){
      //need data in a json format
      echo json_encode($rows);      
    } else {
      echo "Rows not found!";
    }
  }

  public function xhrDeleteListing(){
    $id = $_POST['id'];
    $sql = "DELETE FROM data WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $id ,PDO::PARAM_STR);
    $stmt->execute();
  }
}