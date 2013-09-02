<?php
use Legrand\SPARQLModel;
use Legrand\SPARQL;
use Base\RDFModel;
class RDFError extends RDFModel {

    public $hash                    = null;




    public function __construct(){
       // $this->title = $title;
    }



    protected static $baseURI       = "nodeID://";
    protected static $type          = "http://spinrdf.org/spin#ConstraintViolation";
    protected static $mapping       = [
        'http://purl.org/dc/terms/#submitted' => 'submitted',
    ];


    protected static $multiMapping  = [
        "http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#query" => [
            'property' => 'query',
            'mapping' => 'RDFQuery', //should be the name of the corresponding class
            'inverse' => false,
        ],
        "http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#errorPropertyContext" => [
            'property' => 'errorPropertyContext',
            'mapping' => 'RDFProperty', //should be the name of the corresponding class
            'inverse' => false,
        ],
        "http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#errorClassification" => [
            'property' => 'errorClassification',
            'mapping' => 'RDFAccuracyConcept', //should be the name of the corresponding class
            'inverse' => false,
        ],
        "http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#errorSource" => [
            'property' => 'errorSource',
            'mapping' => 'RDFSourceConcept', //should be the name of the corresponding class
            'inverse' => false,
        ],
        "http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#errorType" => [
            'property' => 'errorType',
            'mapping' => 'RDFErrorType', //should be the name of the corresponding class
            'inverse' => false,
        ],
        "http://spinrdf.org/spin#violationRoot" => [
            'property' => 'violationRoot',
            'mapping' => 'RDFDBpediaResource', //should be the name of the corresponding class
            'inverse' => false,
        ],

        "http://spinrdf.org/spin#violationPath" => [
            'property' => 'violationPath',
            'mapping' => 'RDFProperty', //should be the name of the corresponding class
            'inverse' => false,
        ],
    ];


    public $value;
    protected static $status        = false;

    public function generateID()
    {
    //    if(!isset($this->hash) || !is_string($this->hash) || $this->hash == '') throw new Exception("There is no hash string to generate the unique URI");

        return self::$baseURI . $this->hash;
    }



    public function save($moreData=[])
    {
     //   if(!isset($this->performed)) $this->performed = date('Y-m-d H:i:s', time());
        parent::save($moreData);
    }

    public function load_value(){
        $sparql = new SPARQL();
        $sparql->baseUrl = RDFDBpediaResource::getConfig('sparqlmodel.endpoint');
        $sparql->select(RDFDBpediaResource::getConfig('sparqlmodel.graph'));
        $sparql->variable("?offender");
        $sparql->where("<".$this->violationRoot[0]->identifier.">","<".$this->violationPath[0]->identifier.">","?offender");

        $data = $sparql->launch();
        $this->value = $data["results"]["bindings"][0]["offender"]["value"];


    }

    public function toArray($expand = false){
        $arr = parent::toArray($expand);
        $arr["value"]=$this->value;
        return $arr;
    }

    public static function findTriple($resource, $property, $test, $query){

        $sparql = new SPARQL();
        $sparql->baseUrl = RDFError::getConfig('sparqlmodel.endpoint');
        $sparql->describe($test);
        $sparql->variable("?uri");
        $sparql->where('?uri', 'a',  "<".RDFError::getType().">");
        $sparql->where("?uri","<".self::getUriFromProperty("violationRoot").">" ,"<".$resource.">");
        $sparql->where("?uri", "<".self::getUriFromProperty("violationPath").">" ,"<".$property.">");
        $sparql->where("?uri", "<".self::getUriFromProperty("query").">" ,"<".$query.">");

        $objects = self::listingFromQuery($sparql, $forProperty = false);

        $error = array_filter($objects, function($a){
            if(is_a($a,"RDFError")) return $a;
            else return NULL;
        });

        return reset($error);

    }

}
RDFError::init();