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
       // 'http://www.w3.org/2000/01/rdf-schema#label' => 'label',
        'http://debug.dbpedia.org#queryID' => 'query',
     //   'http://semreco/property/performed' => 'performed'
    ];


    protected static $multiMapping  = [

        "http://purl.org/dc/terms/subject" => [
            'property' => 'subject',
            'mapping' => 'RDFSubject', //should be the name of the corresponding class
            'inverse' => false,
        ],
        "http://spinrdf.org/spin#violationRoot" => [
            'property' => 'violationRoot',
            'mapping' => 'RDFDBpediaResource', //should be the name of the corresponding class
            'inverse' => false,
        ],

        "http://spinrdf.org/spin#violationPath" => [
            'property' => 'inaccurateProperty',
            'mapping' => 'RDFProperty', //should be the name of the corresponding class
            'inverse' => false,
        ],
        "http://debug.dbpedia.org#test" => [
            'property' => 'test',
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
        $sparql->where("<".$this->violationRoot[0]->identifier.">","<".$this->inaccurateProperty[0]->identifier.">","?offender");

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
        $sparql->describe(SPARQLModel::getConfig('sparqlmodel.graph'));
        $sparql->variable("?uri");
        $sparql->where('?uri', 'a',  "<".RDFError::getType().">");
        $sparql->where("?uri", "<http://spinrdf.org/spin#violationRoot>" ,"<".$resource.">");
        $sparql->where("?uri", "<http://spinrdf.org/spin#violationPath>" ,"<".$property.">");
        $sparql->where("?uri", "<http://debug.dbpedia.org#test>" ,"<".$test.">");
        $sparql->where("?uri", "<http://debug.dbpedia.org#queryID>" ,"'".$query."'@en");

        $objects = self::listingFromQuery($sparql, $forProperty = false);

        $error = array_filter($objects, function($a){
            if(is_a($a,"RDFError")) return $a;
            else return NULL;
        });

        return reset($error);

    }

}
RDFError::init();