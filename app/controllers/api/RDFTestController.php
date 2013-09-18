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

        return Response::json(array("name"=>"dbt:20130617"));
    }

    public function getCategories(){

        $test = Input::get('test', null);
        $category = Input::get('category', null);
        return Response::json(RDFSubject::getCategories($test,$category));
    }

    public function getClassification(){

        $test = Input::get('test', null);


        $rdfTest = new RDFTestSet($test);

        $donut = $rdfTest->getTestClassifications();
        $total = $rdfTest->countErrors();

        return Response::json(array("cats"=>$donut,"total"=>$total));
    }


    public function getResources(){
        $test = Input::get('test', null);
        $search = Input::get('search', null);
        $rdfTest = new RDFTestSet($test);
        $resources = $rdfTest->getResources($search);

        return Response::json($resources);

    }
    public function getClassifications(){
        $test = Input::get('test', null);
        $search = Input::get('search', null);
        $rdfTest = new RDFTestSet($test);
        $classes = $rdfTest->getClassifications($search);

        return Response::json($classes);

    }
    public function getQueries(){
        $test = Input::get('test', null);
        $search = Input::get('search', null);
        $rdfTest = new RDFTestSet($test);
        $queries = $rdfTest->getQueries($search);

        return Response::json($queries);

    }

    public function getTypes(){
        $test = Input::get('test', null);
        $search = Input::get('search', null);
        $rdfTest = new RDFTestSet($test);
        $types = $rdfTest->getTypes($search);

        return Response::json($types);

    }
    public function getSources(){
        $test = Input::get('test', null);
        $search = Input::get('search', null);
        $rdfTest = new RDFTestSet($test);
        $sources = $rdfTest->getSources($search);

        return Response::json($sources);

    }


    public function getErrors(){
        $test = Input::get('test', null);
        $query = Input::get('query', null);
        $type = Input::get('type', null);
        $source = Input::get('source', null);
        $class = Input::get('class', null);
        $resource = Input::get('resource', null);
        $rdfTest = new RDFTestSet($test);
        $errors = $rdfTest->getErrors($resource,$source,$type,$class,$query);
        return Response::json($errors);


    }


}
