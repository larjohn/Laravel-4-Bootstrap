<?php
/**
 * Created by PhpStorm.
 * User: larjohns
 * Date: 1/9/2013
 * Time: 9:37 μμ

 */
use Legrand\SPARQLModel;
use Legrand\SPARQL;
use Base\RDFModel;
class RDFQuery  extends RDFModel {

    protected static $type          = "http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#Query";
    protected static $status        = false;

    protected static $mapping       = [
        'http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#sparql' => 'sparql',


    ];
    public static  function getConfig($setting){
        switch($setting){

            case 'sparqlmodel.graph':{
                return 'http://debug.dbpedia.org/queries';
            }
            default:{
            return Config::get($setting);
            }
        }

    }

    public static function getQueries($test){
        $sparql = new SPARQL();
        $sparql->baseUrl= self::getConfig('sparqlmodel.endpoint');


        $sparql->select($test);
        $sparql->variable("?query");
        $sparql->variable("count (?err) as ?count");
        $sparql->where("?err", "<http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#query>", "?query");

        $data = $sparql->launch();

        $results = array("name"=>"query", "children"=>array());
        foreach ($data["results"]["bindings"] as $result) {

            $results["children"][] = array("name"=>$result["query"]["value"], "size"=>$result["count"]["value"]);

        }


        return $results;


    }

    public static function getAllQueries(){
        $sparql = new SPARQL();
        $sparql->baseUrl= self::getConfig('sparqlmodel.endpoint');


        $sparql->select(self::getConfig('sparqlmodel.graph'));
        $sparql->variable("?query");
        $sparql->variable("?sparql");
        $sparql->where("?query", "a", "<".self::$type.">" );

        $sparql->where("?query", "<http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#sparql>", "?sparql");


        $data = $sparql->launch();

        $results = array("name"=>"query", "children"=>array());
        foreach ($data["results"]["bindings"] as $result) {

            $results["children"][] = array("id"=>$result["query"]["value"], "sparql"=>$result["sparql"]["value"]);

        }


        return $results;


    }
}

RDFQuery::init();