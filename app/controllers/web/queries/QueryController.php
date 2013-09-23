<?php
/**
 * User: larjohns
 * Date: 14/6/2013
 * Time: 11:24 μμ
 * To change this template use File | Settings | File Templates.
 */

class QueryController extends BaseController {







    public function  showQueryList(){
        return View::make('queries/list')
            ->with('title',"Queries Index")
            ->with('bread', array("path"=>"queries","params"=>array()))
            ;

    }


}