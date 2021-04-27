<?php

class Megye{

    private $db;

    public function __construct(){
        $this->db=new Database;

    }

    //Returns every row from database
    public function getAllCounty(){
    $sql='SELECT * FROM megye';
    $this->db->query($sql);
    $result=$this->db->resultSet();
    return $result;
    }
    
    public function getMegyenevById($mid){
        $sql='SELECT * FROM megye WHERE id='.$mid;
        $this->db->query($sql);
        $result=$this->db->single();
        return $result->nev;
    }

}