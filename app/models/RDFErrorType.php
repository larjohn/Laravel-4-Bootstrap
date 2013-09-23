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
class RDFErrorType  extends RDFModel {

    protected static $type          = "http://www.w3.org/2002/07/owl#Thing";
    protected static $status        = false;

    protected static $mapping       = [
        'http://www.w3.org/2000/01/rdf-schema#label' => 'label',

    ];


    public static function getTypes($test){
        $sparql = new SPARQL();
        $sparql->baseUrl= self::getConfig('sparqlmodel.endpoint');


        $sparql->select($test);
        $sparql->variable("?type");
        $sparql->variable("count (?err) as ?count");
        $sparql->where("?err", "<http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#errorType>", "?type");

        $data = $sparql->launch();

        $results = array("name"=>"errorType", "children"=>array());
        foreach ($data["results"]["bindings"] as $result) {

            $results["children"][] = array("name"=>$result["type"]["value"], "size"=>$result["count"]["value"]);

        }


        return $results;


    }



    }

RDFErrorType::init();