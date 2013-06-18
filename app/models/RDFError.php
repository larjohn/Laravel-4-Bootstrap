<?php
use Legrand\SPARQLModel;

class RDFError extends SPARQLModel {

    public $hash                    = null;

    public $title;
    public $significance = "important";

    private $query = '
CONSTRUCT {
_:r a <http://spin.org/vialationConstrain> ;
    <http://dbpedia.org/debug/test>   <http://dbpedia.org/debug/Test-20130617> ;
    <http://dbpedia.org/debug/queryID> "Wrong ISBN"@en;
    <http://spin.org/violationRoot> ?s ;
    dcterms:subject <http://dbpedia.org/debug/skos/Books> ;
    dcterms:subject <http://dbpedia.org/debug/skos/ErrorInExtraction> ;
    dcterms:subject <http://dbpedia.org/debug/skos/ErrorInWikipedia> ;
   <http://dbpedia.org/debug/hasInaccurateProperty> dbpedia-owl:isbn .
}
WHERE {
?s dbpedia-owl:isbn ?value .
FILTER (! regex(str(?value), "^([iIsSbBnN 0-9-])*$"))
} limit 5
';

    public function __construct(){
       // $this->title = $title;
    }

    protected static $baseURI       = "http://dbpedia.org/ontology/";
    protected static $type          = "http://www.w3.org/2002/07/owl#Class";
    protected static $mapping       = [
        'http://www.w3.org/2000/01/rdf-schema#label' => 'label',
     //   'http://semreco/property/performed' => 'performed'
    ];


    protected static $multiMapping  = [
        'http://www.w3.org/2000/01/rdf-schema#domain' => [
            'property' => 'domain',
            'mapping' => 'RDFError', //should be the name of the corresponding class
            'inverse' => true,
        ]
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

    public function testQ(){
        $this->listingFromQuery($this->query);
    }
}