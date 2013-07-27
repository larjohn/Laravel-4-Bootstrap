<?php
/**
 * User: larjohns
 * Date: 14/6/2013
 * Time: 11:24 μμ
 * To change this template use File | Settings | File Templates.
 */

class ErrorController extends BaseController {


    public function showLatestTestIndex(){
        return View::make('errors/list')
            ->with('title',"Latest Validation Test Results")
            ->with('bread', array("path"=>"latest", "label"=>"latest test"))
            ->with("mode","latest");


    }




    public function showTestIndex($test)
    {
        //$res =  RDFDBpediaResource::find('http://dbpedia.org/resource/Family_Without_a_Name'); // Same as new User::find('http://semreco/person/damien_legrand');


        // var_dump($res->label);
        if(isset($test)){
            return View::make('errors/list')
                ->with('title',"Errors list for ". $test)
                ->with('bread', array("path"=>"tests.item.all","params"=>array("test"=>$test,"label"=>"Errors List")))
                ->with("mode","item")
                ->with("test", $test);
        }
        else{
            return View::make('errors/list')
                ->with('title',"Errors List")
                ->with('bread', "latest");
        }

    }


    public function showQueryIndex($test){
        return View::make('errors/treemap')
            ->with('title', "Errors by query")
            ->with('bread', array('path'=>"tests.item.queries","params"=>array("test"=>$test,"label"=>"Queries")))
            ->with('test',$test);
    }

}