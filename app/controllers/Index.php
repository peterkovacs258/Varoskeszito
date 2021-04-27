<?php

class Index extends Controller{
    public function __construct(){
        $this->megyeModel=$this->model('Megye');
        $this->varosModel=$this->model('Varos');
    }

    public function index()
    {
        //this array serves the purpos of sending data to the view
        $data=[
            'megyekOptionba'=>""
        ];
        //We ask the model for the counties of hungary
        $megyek=$this->megyeModel->getAllCounty();
        $megyekOptionba="";
        //fill the $megyekOptionba variable with the counties in <option></option> tags
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
            $vnev=$_POST['varosnev'];
            if(!$this->varosModel->varosExists($vnev))
            {
                
                if($this->varosModel->addCityToCounty($mId,$vnev))
                {
                    echo $this->varosokTablazatban($mId);
                }
                else{ echo http_response_code(304);}
                
            }
            else
            {
                echo $this->varosokTablazatban($mId);
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

    //Egy megyeid alapján visszaadja annak városait egy táblázatban
    private function varosokTablazatban($megyeid)
    {
        $megfeleltVarosok=$this->varosModel->getCitiesByCountyId($megyeid);
        $data=$table="<div>Megye: ".$this->megyeModel->getMegyenevById($megyeid)."</div>";
        if($megfeleltVarosok!=false)
        {
            $data.="<table class='table table-hover'>".
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