<?php

class Megye{

    private $db;

    public function __construct(){
        $this->db=new Database;

    }
    //Visszaad minden sort az adatbázisból
    public function getAllCounty(){
    $sql='SELECT * FROM megye';
    $this->db->query($sql);
    $result=$this->db->resultSet();
    return $result;
    }
    //ID alapján visszaadja ahozzá tartozó megy nevét
    public function getMegyenevById($mid){
        $sql='SELECT * FROM megye WHERE id='.$mid;
        $this->db->query($sql);
        $result=$this->db->single();
        return $result->nev;
    }

}