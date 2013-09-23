<?php
/**
 * Created by JetBrains PhpStorm.
 * User: larjohns
 * Date: 16/6/2013
 * Time: 3:56 μμ

 */

class RDFQueryController extends BaseController
{




    public function getList(){
        return Response::json(RDFQuery::getAllQueries());
    }



}
