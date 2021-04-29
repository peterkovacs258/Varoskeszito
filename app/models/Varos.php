<?php

class Varos{

    private $db;
    public function __construct(){
    $this->db=new Database;

    }

    //Visszaad minden várost az adatbázisból
    public function getAllCity(){
    $sql='SELECT * FROM varos';
    $this->db->query($sql);
    $result=$this->db->resultSet();
    return $result;
    }
    // Kilistázza egy adott megye városait az adatbázis alapján
    public function getCitiesByCountyId($mid){
        $sql='SELECT * FROM varos where megyeid=:mid ORDER BY nev';
        $this->db->query($sql);
        $this->db->bind(':mid',$mid);
        $res=$this->db->resultSet();
        if(!empty($res))
        {
            return $res;
        }
        else
        {
            return false;
        } 



    }
    //Hozzáad egy várost az adatbázishoz
    public function addCityToCounty($countyId,$city)
    {
        $sql="INSERT INTO varos (megyeid,nev) VALUES(:cid,:city)";
        $this->db->query($sql);
        $this->db->bind(':cid',$countyId);
        $this->db->bind(':city',$city);
        if($this->db->execute())
        {
            return true;
        }
        else
        {
            return false;
        } 
    }

    //Módosít egy várost az adatbázisban
    public function modifyCity($id,$val)
    {
        $sql="UPDATE varos SET nev=:val WHERE id=:id";
        $this->db->query($sql);
        $this->db->bind(':val',$val);
        $this->db->bind(':id',$id);
        if($this->db->execute())
        {
            return true;
        }
        else 
        {
            return false;
        }
    }

    //Név alapján megnézi hogy létezik-e az adott város
    public function varosExists($vnev)
    {
        $sql="SELECT * FROM varos WHERE nev=:nev";
        $this->db->query($sql);
        $this->db->bind(':nev',$vnev);
        $res=$this->db->resultSet();
        if(count($res)>0)
        {
            return true;
        }
        else 
        {
            return false;
        }

    }
    //Visszaad egy város objektumot id alapján
    public function getCityById($id){
        $sql="SELECT * FROM varos WHERE id=:id";
        $this->db->query($sql);
        $this->db->bind(':id',$id);
        $res=$this->db->single();
       return $res;
    }

    //Töröl egy várost id alapján
    public function deleteCity($id)
    {
        $sql="DELETE FROM `varos` WHERE id=:id";
        $this->db->query($sql);
        $this->db->bind(':id',$id);
        if($this->db->execute())
        {
            return true;
        }
        else 
        {
            return false;
        }
    }

}