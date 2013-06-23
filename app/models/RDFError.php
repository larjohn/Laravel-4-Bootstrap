<?php
use Legrand\SPARQLModel;

class RDFError extends SPARQLModel {

    public $hash                    = null;


    private static  $tquery = '
construct {
?s ?p ?a
}
WHERE {

?s a <http://spinrdf.org/spin#ConstraintViolation>.
?s ?p ?a.
} limit 100
';



    public function __construct(){
       // $this->title = $title;
    }



    protected static $baseURI       = "http://dbpedia.org/ontology/";
    protected static $type          = "http://spinrdf.org/spin#ConstraintViolation";
    protected static $mapping       = [
       // 'http://www.w3.org/2000/01/rdf-schema#label' => 'label',
        'http://dbpedia.org/debug/queryID' => 'query',
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
        "http://dbpedia.org/debug/test" => [
            'property' => 'test',
            'mapping' => 'RDFProperty', //should be the name of the corresponding class
            'inverse' => false,
        ],
    ];

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

    public static  function getAll($page=0, $perPage = 10){
        $query = "DESCRIBE ?uri {?uri a <".self::$type.">} LIMIT ". $perPage. " OFFSET ".$page*$perPage;
        return self::listingFromQuery($query);
    }
}
RDFError::init();