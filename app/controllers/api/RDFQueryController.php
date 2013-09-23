<?php
/**
 * Created by JetBrains PhpStorm.
 * User: larjohns
 * Date: 16/6/2013
 * Time: 3:56 μμ

 */
use Legrand\SPARQLModel;
class RDFQueryController extends BaseController
{




    public function getList(){
        return Response::json(RDFQuery::getAllQueries());
    }

    public function getItem(){
        $id = Input::get('query', null);
        $query = RDFQuery::find($id);

        return Response::json($query->toArray(true));
    }



}
