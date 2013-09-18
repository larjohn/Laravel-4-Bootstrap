<?php
/**
 * Created by JetBrains PhpStorm.
 * User: larjohns
 * Date: 19/6/2013
 * Time: 6:41 μμ

 */
use Legrand\SPARQLModel;
use Legrand\SPARQL;
class RDFTestSet extends SPARQLModel {

    public $test;

    public function  __constructor($test){
        $this->test = $test;
    }

    public function getTestClassifications($top = null){
        $sparql = new SPARQL();
        $sparql->baseUrl= self::getConfig('sparqlmodel.endpoint');

        $sparql->select($this->test);
        $sparql->variable("?class");
        $sparql->variable("count(?err) as ?count" );
        $sparql->where("?err", "<http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#errorClassification>", "?class" );

        
        
        
        $data  = $sparql->launch();

        $results = array();

        foreach ($data["results"]["bindings"] as $result) {
            $results[] = array("class"=>$result["class"]["value"], "count"=>$result["count"]["value"]);
        }


        return $results;



    }


    public function countErrors(){
        $sparql = new SPARQL();
        $sparql->baseUrl= self::getConfig('sparqlmodel.endpoint');

        $sparql->select($this->test);

        $sparql->variable("count(distinct ?err) as ?count" );
        $sparql->where("?err", "a", "<http://spinrdf.org/spin#ConstraintViolation>" );

        $data  = $sparql->launch();



        return $data["results"]["bindings"][0]["count"]["value"];


    }


    public function getResources($search=null){
        $sparql = new SPARQL();
        $sparql->baseUrl= self::getConfig('sparqlmodel.endpoint');

        $sparql->select($this->test);
        $sparql->distinct(true);

        $sparql->variable("?res" );
        $sparql->where("?err", "a", "<http://spinrdf.org/spin#ConstraintViolation>" );
        $sparql->where("?err", "<http://spinrdf.org/spin#violationRoot>","?res");
        $sparql->limit(10);
        if(isset($search)){
            $sparql->filter('regex(?res, "'.str_ireplace(" ","_",$search) .'", "i" ) ');
        }
        else{
            $sparql->orderBy("count(?err)");
        }

        //var_dump($sparql->getQuery());

        $data  = $sparql->launch();




        $results = array();

        foreach ($data["results"]["bindings"] as $result) {
            $results[] = array("value"=>$result["res"]["value"], "label"=>EasyRdf_Namespace::shorten($result["res"]["value"]));
        }


        return $results;





    }

    public function getClassifications($search=null){
        $sparql = new SPARQL();
        $sparql->baseUrl= self::getConfig('sparqlmodel.endpoint');

        $sparql->select($this->test);
        $sparql->distinct(true);

        $sparql->variable("?class" );
        $sparql->where("?err", "a", "<http://spinrdf.org/spin#ConstraintViolation>" );
        $sparql->where("?err", "<http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#errorClassification>","?class");
        $sparql->limit(10);
        if(isset($search)){
            $sparql->filter('regex(?class, "'.str_ireplace(" ","_",$search) .'", "i" ) ');
        }
        else{
            $sparql->orderBy("count(?err)");
        }

        //var_dump($sparql->getQuery());

        $data  = $sparql->launch();




        $results = array();

        foreach ($data["results"]["bindings"] as $result) {
            $results[] = array("value"=>$result["class"]["value"], "label"=>EasyRdf_Namespace::shorten($result["class"]["value"]));
        }


        return $results;





    }   public function getQueries($search=null){
        $sparql = new SPARQL();
        $sparql->baseUrl= self::getConfig('sparqlmodel.endpoint');

        $sparql->select($this->test);
        $sparql->distinct(true);

        $sparql->variable("?query" );
        $sparql->where("?err", "a", "<http://spinrdf.org/spin#ConstraintViolation>" );
        $sparql->where("?err", "<http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#query>","?query");
        $sparql->limit(10);
        if(isset($search)){
            $sparql->filter('regex(?query, "'.str_ireplace(" ","_",$search) .'", "i" ) ');
        }
        else{
            $sparql->orderBy("count(?err)");
        }

        //var_dump($sparql->getQuery());

        $data  = $sparql->launch();




        $results = array();

        foreach ($data["results"]["bindings"] as $result) {
            $results[] = array("value"=>$result["query"]["value"], "label"=>EasyRdf_Namespace::shorten($result["query"]["value"]));
        }


        return $results;





    }   public function getSources($search=null){
        $sparql = new SPARQL();
        $sparql->baseUrl= self::getConfig('sparqlmodel.endpoint');

        $sparql->select($this->test);
        $sparql->distinct(true);

        $sparql->variable("?source" );
        $sparql->where("?err", "a", "<http://spinrdf.org/spin#ConstraintViolation>" );
        $sparql->where("?err", "<http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#errorSource>","?source");
        $sparql->limit(10);
        if(isset($search)){
            $sparql->filter('regex(?source, "'.str_ireplace(" ","_",$search) .'", "i" ) ');
        }
        else{
            $sparql->orderBy("count(?err)");
        }

        //var_dump($sparql->getQuery());

        $data  = $sparql->launch();




        $results = array();

        foreach ($data["results"]["bindings"] as $result) {
            $results[] = array("value"=>$result["source"]["value"], "label"=>EasyRdf_Namespace::shorten($result["source"]["value"]));
        }


        return $results;





    }
    public function getTypes($search=null){
        $sparql = new SPARQL();
        $sparql->baseUrl= self::getConfig('sparqlmodel.endpoint');

        $sparql->select($this->test);
        $sparql->distinct(true);

        $sparql->variable("?type" );
        $sparql->where("?err", "a", "<http://spinrdf.org/spin#ConstraintViolation>" );
        $sparql->where("?err", "<http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#errorType>","?type");
        $sparql->limit(10);
        if(isset($search)){
            $sparql->filter('regex(?type, "'.str_ireplace(" ","_",$search) .'", "i" ) ');
        }
        else{
            $sparql->orderBy("count(?err)");
        }

        //var_dump($sparql->getQuery());

        $data  = $sparql->launch();




        $results = array();

        foreach ($data["results"]["bindings"] as $result) {
            $results[] = array("value"=>$result["type"]["value"], "label"=>EasyRdf_Namespace::shorten($result["type"]["value"]));
        }


        return $results;





    }


    public function getErrors($resource=null,$source=null, $type=null, $class= null,$query= null){
        $sparql = new SPARQL();
        $sparql->baseUrl= self::getConfig('sparqlmodel.endpoint');

        $sparql->select($this->test);
        $sparql->distinct(true);
        $sparql->variable("?err" );
        $sparql->variable("?type" );
        $sparql->variable("?source" );
        $sparql->variable("?query" );
        $sparql->variable("?res" );
        $sparql->where("?err", "a", "<http://spinrdf.org/spin#ConstraintViolation>" );
        $sparql->where("?err", "<http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#errorType>","?type");
        $sparql->where("?err", "<http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#errorSource>","?source");
        $sparql->where("?err", "<http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#query>","?query");
        $sparql->where("?err", "<http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#errorClassification>","?class");
        $sparql->where("?err", "<http://spinrdf.org/spin#violationRoot>","?res");


        if(isset($type)){
            $sparql->where("?err", "<http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#errorType>","<$type>");

        }
        if(isset($source)){
            $sparql->where("?err", "<http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#errorSource>","<$source>");

        }
        if(isset($query)){
            $sparql->where("?err", "<http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#query>","<$query>");

        }
        if(isset($class)){
            $sparql->where("?err", "<http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#errorClassification>","<$class>");

        }
        if(isset($resource)){
            $sparql->where("?err", "<http://spinrdf.org/spin#violationRoot>","<?res>");

        }




        $sparql->orderBy("count(?err)");


        //var_dump($sparql->getQuery());

        $data  = $sparql->launch();


        var_dump($data);


        $results = array();

        foreach ($data["results"]["bindings"] as $result) {
            $results[] = array("value"=>$result["type"]["value"], "label"=>EasyRdf_Namespace::shorten($result["type"]["value"]));
        }


        return $results;





    }




}

RDFTestSet::init();