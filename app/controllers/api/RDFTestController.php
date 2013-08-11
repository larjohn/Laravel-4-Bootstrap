<?php
/**
 * Created by JetBrains PhpStorm.
 * User: larjohns
 * Date: 16/6/2013
 * Time: 3:56 μμ

 */

class RDFTestController extends BaseController
{
    public  function getLatest(){
       // $collection = new RDFErrorCollection();

        return Response::json(array("name"=>"dbgtests:Test-20130617"));
    }


}
