<?php
/**
 * User: larjohns
 * Date: 14/6/2013
 * Time: 11:24 μμ
 * To change this template use File | Settings | File Templates.
 */

class ListController extends BaseController {


    public function showLatest(){
        return View::make('list')
            ->with('title',"Latest Validation Test Results")
            ->with('bread', "latest")
            ->with("mode","latest");


    }


    public function showIndex($test)
    {
        //$res =  RDFDBpediaResource::find('http://dbpedia.org/resource/Family_Without_a_Name'); // Same as new User::find('http://semreco/person/damien_legrand');


        // var_dump($res->label);
        if(isset($test)){
            return View::make('list')
                ->with('title',"Errors List")
                ->with('bread', "latest")
                ->with("mode","item")
                ->with("test", $test);
        }
        else{
            return View::make('list')
                ->with('title',"Errors List")
                ->with('bread', "latest");
        }

    }


}