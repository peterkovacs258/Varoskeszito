<?php
class Database
{
    private $dbHost= DB_HOST;
    private $dbUser= DB_USER;
    private $dbPass = DB_PASS;
    private $dbName = DB_NAME;


    private $statement;
    private $dbHandler;
    private $error;

    public function __construct(){
        $conn='mysql:host='.$this->dbHost.';dbname='.$this->dbName;
        $options =array(
            PDO::ATTR_PERSISTENT=> true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        try{
            $this->dbHandler=new PDO($conn,$this->dbUser,$this->dbPass,
            $options);

        }catch(PDOException $ex)
        {
            $this->error=$ex->getMessage();
            echo $this->error;
        }

    

    }

    //query
    public function query($sql)
    {
        $this->statement=$this->dbHandler->prepare($sql);
    }
    //Bind
    public function bind($parameter, $value,$type=null)
    {
        switch(is_null($type)){
            case is_int($value): $type=PRO::PARAM_INT;
            break;
            case is_bool($value): $type=PRO::PARAM_BOOL;
            break;
            case is_null($value): $type=PRO::PARAM_NULL;
            break;
            default: $type=PDO::PARAM_STR;

        }
        $this->statement->bindValue($parameter, $value,$type);
    }
    //Execute
    public function execute()
    {
        return $this->statement->execute();
    }
    //Egy objektum tömböt ad vissza
    public function resultSet()
    {
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_OBJ);
    }
    //Egy rekordot ad vissza, objekumként
    public function single()
    {
        $this->execute();
        return $this->statement->fetch((PDO::FETCH_OBJ));

    }

    //Visszaadja a talált rekordok számát
    public function rowCount(){
        return $this->statement->rowCount();
    }


}
