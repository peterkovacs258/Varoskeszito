<?php

class Index extends Controller{
    public function __construct(){
        $this->megyeModel=$this->model('Megye');
        $this->varosModel=$this->model('Varos');
    }

    public function index()
    {
        //Ez a tömb küld információkat a view nek
        $data=[
            'megyekOptionba'=>""
        ];
        $megyek=$this->megyeModel->getAllCounty();
        $megyekOptionba="";
        //Feltöltjük a $megyekOptionba változót, Magyarország megyéivel, <option></option> tag-ekben
        foreach($megyek as $item)
        {
            $megyekOptionba.="<option value='".$item->id."'>".$item->nev."</option>";
        }
        $data['megyekOptionba']=$megyekOptionba;

        $this->view('index',$data);
    }

    //Ajax kérés alapján megjelenítjük egy megyéhez tartozó városokat
    public function varosokAjax()
    {
    if($_SERVER['REQUEST_METHOD']=='POST'&&isset($_POST['megyeid']))
        {
         $mId=$_POST['megyeid'];
         echo $this->varosokTablazatban($mId);
        }
    }

    //Ajax kérés alapján felveszünk egy új várost
    public function ujVarosAjax()
    {
        if($_SERVER['REQUEST_METHOD']=='POST'&&isset($_POST['megyeid'])&&isset($_POST['varosnev']))
        {
            $mId=$_POST['megyeid'];
            $vnev=ucfirst($_POST['varosnev']);
            //Megnézzük hogy már létezik-e a város az adatbázisban
            if(!$this->varosModel->varosExists($vnev))
            {
                
                if($this->varosModel->addCityToCounty($mId,$vnev))
                {
                    echo $this->varosokTablazatban($mId);
                }
                else{ 
                    //Sikertelen hozzáadás
                    echo $this->varosokTablazatban($mId);
                }
                
            }
            else
            {
                //Ha már létezik hibakóddal térünk vissza
                http_response_code(403);
            }
           
            
            
        }
        
    }

    //Ajax kérés alapján törlünk egy várost
    public function torolVarosAjax()
    {
        if($_SERVER['REQUEST_METHOD']=='POST'&&isset($_POST['id']))
        {
            $id=$_POST['id'];
            $mid=$this->varosModel->getCityById($id)->megyeid;
            
                if($this->varosModel->deleteCity($id))
                {
                    echo $this->varosokTablazatban($mid);
                }
                

            
        }
    }
    //Ajax kérés alapján módosítunk egy várost
    public function modositVarosAjax(){
        if($_SERVER['REQUEST_METHOD']=="POST"&&isset($_POST['id'])&&isset($_POST['modCity'])&&isset($_POST['megyeid']))
        {
            $id=$_POST['id'];
            $val=$_POST['modCity'];
            $mid=$_POST['megyeid'];
            if($this->varosModel->modifyCity($id,$val))
            {
                //siker
                echo $this->varosokTablazatban($mid);

            }
            else
            {
                //hiba történt
                echo $this->varosokTablazatban($mid);
                
            }
        }
    }

    //Egy megyeid alapján visszaadja annak városait egy táblázatban
    private function varosokTablazatban($megyeid)
    {
        $megfeleltVarosok=$this->varosModel->getCitiesByCountyId($megyeid);
        $data=$table="<div class='currentMegyeDiv'>Megye: ".$this->megyeModel->getMegyenevById($megyeid)."</div>";
        if($megfeleltVarosok!=false)
        {
            $data.="<table class='table table-hover table-dark'>".
                         "<thead'>".
                          "<tr>".
                              "<th>Városok</th>".
                          "</tr>".
                         "</thead>";
        foreach($megfeleltVarosok as $item)
        {
         $data.="<tr>".
                      "<td data-id=".$item->id.">".$item->nev."</td>".
                  "<tr>";
         }
         $data.="</table>";
        }
        return $data;

    }

}