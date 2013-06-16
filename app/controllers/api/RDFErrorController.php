<?php
/**
 * Created by JetBrains PhpStorm.
 * User: larjohns
 * Date: 16/6/2013
 * Time: 3:56 μμ

 */

class RDFErrorController extends BaseController {

    public function index()
    {
        if(Input::has("significance")){
            $e2 = new  RDFError("Jenny");
            return Response::json([$e2]);
        }
        $e1 = new RDFError("Joe");
        $e1->significance = "urgent";
        $e2 = new  RDFError("Jenny");
        $e3 = new RDFError("James");
        return Response::json([$e1,$e2,$e3]);
    }


}