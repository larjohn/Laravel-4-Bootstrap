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

       $rdf_errors = RDFError::testQ();
       $errors = array();
        foreach ($rdf_errors as $rdf_error) {
            if(!is_a($rdf_error,"RDFError")) continue;
            $error= $rdf_error->toArray();
            $errors[] = $error;

        }



        return Response::json($errors);
    }


}