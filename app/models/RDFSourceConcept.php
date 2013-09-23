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
class RDFSourceConcept  extends RDFModel {

    protected static $type          = "http://www.w3.org/2002/07/owl#Thing";
    protected static $status        = false;

    protected static $mapping       = [
        'http://www.w3.org/2000/01/rdf-schema#label' => 'label',
    ];

    protected static $multiMapping  = [
        "http://purl.org/dc/terms/subject" => [
            'property' => 'category',
            'mapping' => 'RDFSubject', //should be the name of the corresponding class
            'inverse' => false,
        ],
    ];

    public static function getSources($test){
        $sparql = new SPARQL();
        $sparql->baseUrl= self::getConfig('sparqlmodel.endpoint');


        $sparql->select($test);
        $sparql->variable("?source");
        $sparql->variable("count (?err) as ?count");
        $sparql->where("?err", "<http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#errorSource>", "?source");

        $data = $sparql->launch();

        $results = array("name"=>"errorSource", "children"=>array());
        foreach ($data["results"]["bindings"] as $result) {

            $results["children"][] = array("name"=>$result["source"]["value"], "size"=>$result["count"]["value"]);

        }


        return $results;


    }
}

RDFSourceConcept::init();