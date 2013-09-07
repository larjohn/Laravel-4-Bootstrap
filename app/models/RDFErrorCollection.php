<?php
/**
 * Created by JetBrains PhpStorm.
 * User: larjohns
 * Date: 27/6/2013
 * Time: 1:24 πμ

 */
use Legrand\SPARQL;
use Legrand\SPARQLModel;
use Base\RDFModel;

/**
 * Class RDFErrorCollection
 */
class RDFErrorCollection
{

    /**
     * @var array
     */
    public static $enabledFacets = array(
        "query",
        array("violationRoot", "category"),
        "errorClassification",
        "errorSource",
        "errorType",

    );
    /**
     * @var array
     */
    protected $currentFilters = array();

    /**
     *
     */
    public function __construct()
    {

    }

    /**
     * @param array $filters
     */
    public function setFilters($filters = array())
    {

        foreach ($filters as $key => $filter) {
            if ($key == 'undefined') continue;

            $this->currentFilters[$filter["name"]] = array(
                "name" => explode('.',$filter["name"]),
                "operator" => $filter["operator"],
                "value" => EasyRdf_Namespace::expand($filter["value"]),

            );
        }
    }

    /**
     * @param string $variable select a single facet giving its path
     * @return array
     */
    public function getFacets($test, $variable = "", $limit = 8)
    {


        $facetResults = array();
        foreach (self::$enabledFacets as $facet) {
            $class = "RDFError";
            if ($variable != "" && $facet != $variable)
                continue;

            $sparql = new SPARQL();
            $sparql->baseUrl = RDFError::getConfig('sparqlmodel.endpoint');
            $sparql->select(SPARQLModel::getConfig(null));

            if (!is_array($facet)) {
                $property_uri = $class::getUriFromProperty($facet);

                $var = $title = $facet;

                $sparql->where("?uri", "<" . $property_uri . ">", "?" . $facet, $test);
                $sparql->variable("count(?" . $facet . ") as ?count");
                $sparql->variable("?" . $facet);
            } else {

                $property_uri = $class::getUriFromProperty($facet[0]);
                $sparql->where("?uri", "<" . $property_uri . ">", "?" . $facet[0],$test);


                for ($i = 1; $i < count($facet); $i++) {
                    //echo $property_uri;
                    if (isset($class::getMultiMapping()[$property_uri])) {

                        $class = $class::getMultiMapping()[$property_uri]["mapping"];
                    } else break;
                    $property_uri = $class::getUriFromProperty($facet[$i]);

                    $graph = $class::getConfig('sparqlmodel.graph');
                    if(isset($class::getMultiMapping()[$property_uri]["graph"])){
                        $graph = $class::getMultiMapping()[$property_uri]["graph"];
                    }

                    $sparql->where("?" . $facet[$i - 1], "<" . $property_uri . ">", "?" . $facet[$i], $graph);

                }
                $sparql->variable("count(?" . $facet[$i - 1] . ") as ?count");
                $var = $facet[$i - 1];
                $title = implode(".", $facet);
                $sparql->variable("?" . $facet[$i - 1]);
            }

            if ($limit > 0) {
            }

            $this->applyFilters($sparql);
            $sparql->orderBy("?count", "desc");
            $sparql->bound(false);
            $sparql->limit($limit);
            //var_dump($sparql->getQuery());//die;
            $data = $sparql->launch();
           // continue;


            $facetArray = array();
            $facetArray["title"] = $title;

            //var_dump($this->currentFilters);
            if(isset($this->currentFilters[$title])){
                $facetArray["state"] = "active";
            }

            foreach ($data["results"]["bindings"] as $fct) {

                $state = "none";

                if(isset($this->currentFilters[$title])){
                    if( $fct[$var]["value"] == $this->currentFilters[$title]["value"]){
                        $state = "selected";
                    }
                }

               // var_dump($var);
                //var_dump($fct[$var]["value"]);


                $facetArray["elements"][] = array(
                    "value" => $fct[$var]["value"],
                    "label" => $fct[$var]["value"],
                    "count" => $fct["count"]["value"],
                    "state" => $state,
                );
            }

            $facetResults[$title] = $facetArray;
        }


        return $facetResults;
    }

    /**
     * Applies the filters that were set using setFilters to the
     * given sparql query stracture
     * @param $sparql A Sparql query structure to be filtered
     */
    private function applyFilters($sparql)
    {

        foreach ($this->currentFilters as $filter) {
            $class = "RDFError";
            if(count($filter["name"])<2){
                $propertyUri = $class::getUriFromProperty($filter["name"][0]);
                if ($propertyUri == "") continue;
                if (isset($class::getMultiMapping()[$propertyUri])) {

                    $sparql->where('?uri', "<" . $propertyUri . ">", "<" . $filter["value"] . ">");
                } else {
                    $sparql->where('?uri', "<" . $propertyUri . ">", "?" . $filter["name"][0]);
                    $sparql->filter("str(?" . $filter["name"][0] . ") " . $filter["operator"] . " '" . $filter["value"] . "'");

                }

            }

            else{
                $propertyUri = $class::getUriFromProperty($filter["name"][0]);
                $sparql->where('?uri', "<" . $propertyUri . ">", "?" . $filter["name"][0]);

                if ($propertyUri == "") continue;


                for($i=1;$i<count($filter["name"])-1;$i++){
                    if (isset($class::getMultiMapping()[$propertyUri])) {

                        $class = $class::getMultiMapping()[$propertyUri]["mapping"];

                        $propertyUri = $class::getUriFromProperty($filter["name"][$i]);

                        $sparql->where("?" . $filter["name"][$i - 1], "<" . $propertyUri . ">", "?" . $filter["name"][$i]);

                    } else break;

                }
                $last = count($filter["name"])-1;
                $propertyUri = $class::getUriFromProperty($filter["name"][$last-1]);

                $class = $class::getMultiMapping()[$propertyUri]["mapping"];

               // echo $class;
                $propertyUri = $class::getUriFromProperty($filter["name"][$last]);

                if (isset($class::getMultiMapping()[$propertyUri])) {



                    $sparql->where('?'.$filter["name"][$last-1], "<" . $propertyUri . ">", "<" . $filter["value"] . ">");
                } else {
                                 //     $sparql->filter("str(?" . $filter["name"][$i-1] . ") " . $filter["operator"] . " '" . $filter["value"] . "'");

                }

            }



        }
        //var_dump($sparql->getQuery());//die;
    }

    /**
     * @param int $page
     * @param int $perPage
     * @param array $sortProperty
     * @param $sortOrder
     * @return array
     */
    public function getAll($test=null, $page = 0, $perPage = 10, $sortProperty = array(), $sortOrder)
    {
        $sparql = new SPARQL();
        $sparql->baseUrl = RDFError::getConfig('sparqlmodel.endpoint');
        if(isset($test)){
            $sparql->bound(true);
            $sparql->describe(null);
        }
        else{
            $sparql->bound(false);
            $sparql->describe("?g");
        }

        $sparql->variable("?" . 'uri');
        $sparql->where('?uri', 'a', "<" . RDFError::getType() . ">", $test);
        //var_dump($sparql->getQuery());
        if (count($sortProperty) > 0) {
            $sorter = "uri";
            $class = "RDFError";
            $ordered = false;
            foreach ($sortProperty as $sp) {
                if ($sp == "id") {
                    $ordered = true;
                    break;
                }
                $propertyUri = $class::getUriFromProperty($sp);
                if ($propertyUri == "") continue;
                $sparql->where('?uri', "<" . $propertyUri . ">", "?" . $sp);
                if (isset($class::getMultiMapping()[$propertyUri])) {

                    $class = $class::getMultiMapping()[$propertyUri]["mapping"];

                }

                $ordered = true;
                $sorter = $sp;

                if ($class::getConfig('sparqlmodel.endpoint') != RDFError::getConfig('sparqlmodel.endpoint'))
                    break;
            }

            if ($ordered) $sparql->orderBy("?" . $sorter, $sortOrder);
        }
        $this->applyFilters($sparql);
        $sparql->limit($perPage);
        $sparql->offset($page * $perPage);
        $count = self::getCount();

        //var_dump($sparql->getQuery());//die;

        return array("count" => $count, "data" => RDFError::listingFromQuery($sparql));
    }

    /**
     * @return int
     */
    public function getCount()
    {
        $sparql = new SPARQL();
        $sparql->baseUrl = RDFError::getConfig('sparqlmodel.endpoint');


        $sparql->variable('count(?uri)');
        $sparql->where('?uri', 'a', "<" . RDFError::getType() . ">");
        $this->applyFilters($sparql);

        $data = $sparql->launch();
//echo $sparql->sparql;die;
        //var_dump($this->currentFilters);
        foreach ($data["results"]["bindings"][0] as $callret) {
            return $callret["value"];
        }
        return 0;
    }
}