<?php

class Controller
{
    //Betölti a modelt
    public function model($model)
    {
        require_once '../app/models/'.$model.'.php';
        return new $model();
    }

    //Betölti a VIEW et, illetve figyelmeztet ha az nem létezik
    public function view($view,$data=[])
    {
        if(file_exists('../app/views/'.$view.'.php'))
        {
            require_once('../app/views/'.$view.'.php');
        }
        else
        {
            die('view does not exists');
        }
    }

}
