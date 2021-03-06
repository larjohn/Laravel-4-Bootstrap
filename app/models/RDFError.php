<?php
use Legrand\SPARQLModel;
use Legrand\SPARQL;
use Base\RDFModel;
class RDFError extends RDFModel {

    public $hash                    = null;




    public function __construct(){
       // $this->title = $title;
    }



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



    public function save($moreData=[])
    {
     //   if(!isset($this->performed)) $this->performed = date('Y-m-d H:i:s', time());
        parent::save($moreData);
    }

    public function load_value($path, $name= "offender" ){
        $sparql = new SPARQL();
        $sparql->baseUrl = RDFDBpediaResource::getConfig('sparqlmodel.resources-endpoint');
        $sparql->select(null);
        $sparql->variable("?".$name);
        $sparql->where("<".$this->violationRoot[0]->identifier.">","<".$path.">","?".$name);

        //var_dump($sparql->getQuery());die;
        $data = $sparql->launch();

       // var_dump($data);
        if(!isset($this->value[$name]))
            $this->value[$name]= array();

        foreach($data["results"]["bindings"] as $result){
            $this->value[$name][] = array("path" => $path, "value" => $result[$name]["value"]);
        }



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

        $error = reset($error);
        //var_dump($error->violationRoot[0]->identifier);die;
        RDFDBpediaResource::lazyLoad(array($error->violationRoot[0]), ["category"],    RDFError::getConfig('sparqlmodel.endpoint'));

       // var_dump($error);

        return $error;

    }

}
RDFError::init();