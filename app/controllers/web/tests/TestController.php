<?php
/**
 * User: larjohns
 * Date: 14/6/2013
 * Time: 11:24 μμ
 * To change this template use File | Settings | File Templates.
 */

class TestController extends BaseController {






    public function showTestOverview($test)
    {

        $test = urldecode($test);

            return View::make('tests/overview')
                ->with('title',"Overview of ". $test)
                ->with('bread', array("path"=>"tests.item","params"=>array("label"=>$test, "test"=>$test)))
                ->with("mode","item")
                ->with("test", $test);


    }


    public function  showTestList(){
        return View::make('tests/list')
            ->with('title',"Tests Index")
            ->with('bread', array("path"=>"tests","params"=>array()))
            ;

    }


}