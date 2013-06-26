<?php
/**
 * Created by JetBrains PhpStorm.
 * User: larjohns
 * Date: 27/6/2013
 * Time: 1:24 πμ

 */
use Legrand\SPARQLModel;
use Legrand\SPARQL;
use Base\RDFModel;
class RDFErrorCollection {

    public static $enabledFacets= array("test","subject","query");

    protected $currentFilters= array();

    public function __construct(){

    }

    public function setFilters($filters=array()){
        foreach ($filters as $filter) {
            $this->currentFilters[$filter["name"]] = array(
              "operator"  => $filter["operator"],
              "value"  => $filter["value"],

            );
        }
    }


    public function getFacets(){
        $facetCounters = array();
        foreach (self::$enabledFacets as $facet) {
            $sparql = new SPARQL();
            $sparql->baseUrl = RDFError::getConfig('sparqlmodel.endpoint');
            $sparql->select(SPARQLModel::getConfig('sparqlmodel.graph'));
        }

    }

    private function applyFilters($sparql){
        $class = "RDFError";
        foreach ($this->currentFilters as $filter) {
            $propertyUri = $class::getUriFromProperty($filter["name"]);
            if($propertyUri=="")continue;
            ///an einai apli tripleta, alliws theloume kai filter 
            $sparql->where('?uri', "<".$propertyUri.">",  "?".$filter["name"]);
        }

    }


    public function getCount(){
        $sparql = new SPARQL();
        $sparql->baseUrl = RDFError::getConfig('sparqlmodel.endpoint');
        $sparql->select(SPARQLModel::getConfig('sparqlmodel.graph'));

        $sparql->variable('count(?uri)');
        $sparql->where('?uri', 'a',  "<".RDFError::getType().">");
        $data = $sparql->launch();
        foreach($data["results"]["bindings"][0] as $callret){
            return $callret["value"];
        }
    }



    public function getAll($page=0, $perPage = 10, $sortProperty =array(), $sortOrder){
        $sparql = new SPARQL();
        $sparql->baseUrl = RDFError::getConfig('sparqlmodel.endpoint');
        $sparql->describe(SPARQLModel::getConfig('sparqlmodel.graph'));
        $sparql->variable("?".'uri');
        $sparql->where('?uri', 'a',  "<".RDFError::getType().">");
        if(count($sortProperty)>0) {
            $sorter = "uri";
            $class = "RDFError";
            $ordered = false;
            foreach($sortProperty as $sp){
                if($sp=="id") {
                    $ordered = true;
                    break;
                }
                $propertyUri = $class::getUriFromProperty($sp);
                if($propertyUri=="")continue;
                $sparql->where('?uri', "<".$propertyUri.">",  "?".$sp);
                if(isset($class::getMultiMapping()[$propertyUri])){

                    $class = $class::getMultiMapping()[$propertyUri]["mapping"];

                }

                $ordered = true;
                $sorter = $sp;

                if($class::getConfig('sparqlmodel.endpoint')!=RDFError::getConfig('sparqlmodel.endpoint'))
                    break;
            }

            if($ordered)$sparql->orderBy("?".$sorter, $sortOrder);
        }
        $sparql->limit($perPage);
        $sparql->offset($page*$perPage);
        $count = self::getCount();

        return array("count" =>$count, "data"=> RDFError::listingFromQuery($sparql));
    }
}