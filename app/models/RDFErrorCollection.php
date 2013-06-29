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

    public static $enabledFacets= array("query");

    protected $currentFilters= array();

    public function __construct(){

    }

    public function setFilters($filters=array()){
        foreach ($filters as $key=>$filter) {
            if($key=='undefined')continue;
            $this->currentFilters[$filter["name"]] = array(
                "name"=>$filter["name"],
              "operator"  => $filter["operator"],
              "value"  => $filter["value"],

            );
        }
    }


    public function getFacets(){
        $class = "RDFError";
        $facetResults = array();
        foreach (self::$enabledFacets as $facet) {
            $sparql = new SPARQL();
            $sparql->baseUrl = RDFError::getConfig('sparqlmodel.endpoint');
            $sparql->select(SPARQLModel::getConfig('sparqlmodel.graph'));
            $var = $facet;
            $property_uri = $class::getUriFromProperty($facet);
            $sparql->variable("?".$var);
            $sparql->variable("count(?".$var.") as ?count");
            $sparql->where("?uri","<".$property_uri.">","?".$var);

            $this->applyFilters($sparql);
            $sparql->orderBy("?count","desc");
            $data = $sparql->launch();


            $facetArray = array();
            $facetArray["title"]= $var;
            foreach($data["results"]["bindings"] as $fct){

                $facetArray["elements"][]=array(
                    "value"=>$fct[$var]["value"],
                    "label"=>$fct[$var]["value"],
                    "count"=>$fct["count"]["value"],
                );
            }

            $facetResults[$var]= $facetArray;
        }


        return $facetResults;
    }

    private function applyFilters($sparql){
        $class = "RDFError";
        foreach ($this->currentFilters as $filter) {
            $propertyUri = $class::getUriFromProperty($filter["name"]);
            if($propertyUri=="")continue;
            if(isset($class::getMultiMapping()[$propertyUri])){
                $sparql->where('?uri', "<".$propertyUri.">",  "?".$filter["value"]);
            }
            else{
                $sparql->where('?uri', "<".$propertyUri.">",  "?".$filter["name"]);
                $sparql->filter("str(?".$filter["name"].") ".$filter["operator"]." '".$filter["value"]."'"   );

            }

        }

    }


    public function getCount(){
        $sparql = new SPARQL();
        $sparql->baseUrl = RDFError::getConfig('sparqlmodel.endpoint');
        $sparql->select(SPARQLModel::getConfig('sparqlmodel.graph'));

        $sparql->variable('count(?uri)');
        $sparql->where('?uri', 'a',  "<".RDFError::getType().">");
        $this->applyFilters($sparql);

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
        $this->applyFilters($sparql);
        $sparql->limit($perPage);
        $sparql->offset($page*$perPage);
        $count = self::getCount();

        return array("count" =>$count, "data"=> RDFError::listingFromQuery($sparql));
    }
}