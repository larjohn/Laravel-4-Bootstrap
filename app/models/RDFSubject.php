<?php
/**
 * Created by JetBrains PhpStorm.
 * User: larjohns
 * Date: 19/6/2013
 * Time: 6:41 μμ

 */
use Legrand\SPARQLModel;
use Legrand\SPARQL;
class RDFSubject extends SPARQLModel
{
    protected static $type = "http://purl.org/dc/terms/subject";


    protected static $mapping = [
        'http://www.w3.org/2000/01/rdf-schema#label' => 'label',

    ];

    public static function getQueriesByCategory($test,$category = null,$depth=3 ){
        $sparql = new SPARQL();
        $sparql->baseUrl= self::getConfig('sparqlmodel.endpoint');

        $cat_sparql = new SPARQL();
        $cat_sparql->baseUrl= self::getConfig('sparqlmodel.endpoint');



        for($i=0;$i<$depth;$i++){

            $sp = new SPARQL();

            $sp->where("?cm".$i."_"."0", "<http://www.w3.org/2004/02/skos/core#broader>","?cm0");



            for($j=1; $j<$i; $j++){
                $sp->where("?cm".$i."_".$j, "<http://www.w3.org/2004/02/skos/core#broader>","?cm".$i."_".( $j-1));
            }
            if(isset($category))
                $sp->where("?c0", "<http://www.w3.org/2004/02/skos/core#broader>", "<".$category.">");
            else
                $sp->filterNotExists("?c0 <http://www.w3.org/2004/02/skos/core#broader> ?z");

            $sp->where("?cm0", "<http://www.w3.org/2004/02/skos/core#broader>", "?c0");

            $sp->filterExists("GRAPH<$test> {?err <http://spinrdf.org/spin#violationRoot> ?res}");
            $sp->where("?res","<http://purl.org/dc/terms/subject>","?cm".$i."_".( $j-1));
            $sp->where("?err","<http://persistence.uni-leipzig.org/nlp2rdf/ontologies/ecn#query>","?query");
            $sparql->union($sp);

        }

        //var_dump($sp->getQuery());





        $sparql->select(null);
        if(isset($category))
            $sparql->where("?c0", "<http://www.w3.org/2004/02/skos/core#broader>", "<".$category.">");
        else
            $sparql->filterNotExists("?c0 <http://www.w3.org/2004/02/skos/core#broader> ?z");

        $sparql->where("?cm0", "<http://www.w3.org/2004/02/skos/core#broader>", "?c0");
        $sparql->optionalWhere("?res","<http://purl.org/dc/terms/subject>" ,"?cm0");


        $sparql->variable("COUNT(?err) as ?count");
        $sparql->variable("?query");

        $sparql->groupby("?query");
        $sparql->having("count(?err)>0");
        $sparql->orderBy("count(?err)", "desc");
        $sparql->limit(30);






        //  $sparql->variable("?".$name);

        // $sparql->where("<".$this->violationRoot[0]->identifier.">","<".$path.">","?".$name);
        //var_dump($sparql->getQuery());die;
        $data = $sparql->launch();

        //  var_dump($data);

        //    if(!isset($this->value[$name]))
        // $this->value[$name]= array();

        $queries = array();
        foreach($data["results"]["bindings"] as $result){
            $queries[] = array("query"=>$result["query"]["value"], "count"=>$result["count"]["value"]);



        }







        return $queries;
    }

    public static function getCategories($test, $category=null, $depth=6)
    {
        $sparql = new SPARQL();
        $sparql->baseUrl= self::getConfig('sparqlmodel.endpoint');

        $cat_sparql = new SPARQL();
        $cat_sparql->baseUrl= self::getConfig('sparqlmodel.endpoint');



        for($i=0;$i<$depth;$i++){

            $sp = new SPARQL();

            $sp->where("?cm".$i."_"."0", "<http://www.w3.org/2004/02/skos/core#broader>","?cm0");



            for($j=1; $j<$i; $j++){
                $sp->where("?cm".$i."_".$j, "<http://www.w3.org/2004/02/skos/core#broader>","?cm".$i."_".( $j-1));
            }
            if(isset($category))
                $sp->where("?c0", "<http://www.w3.org/2004/02/skos/core#broader>", "<".$category.">");
            else
                $sp->filterNotExists("?c0 <http://www.w3.org/2004/02/skos/core#broader> ?z");

            $sp->where("?cm0", "<http://www.w3.org/2004/02/skos/core#broader>", "?c0");

            $sp->filterExists("GRAPH<$test> {?err <http://spinrdf.org/spin#violationRoot> ?res}");
            $sp->where("?res","<http://purl.org/dc/terms/subject>","?cm".$i."_".( $j-1));
            $sparql->union($sp);

        }

           //var_dump($sp->getQuery());





        $sparql->select(null);
        if(isset($category))
            $sparql->where("?c0", "<http://www.w3.org/2004/02/skos/core#broader>", "<".$category.">");
        else
           $sparql->filterNotExists("?c0 <http://www.w3.org/2004/02/skos/core#broader> ?z");

        $sparql->where("?cm0", "<http://www.w3.org/2004/02/skos/core#broader>", "?c0");
        $sparql->optionalWhere("?res","<http://purl.org/dc/terms/subject>" ,"?cm0");


        $sparql->variable("COUNT(?res) as ?count");
        $sparql->variable("?cm0");
        $sparql->variable("?c0");
        $sparql->groupby("?c0 ?cm0");
        $sparql->having("count(?res)>0");
        $sparql->orderBy("count(?res)", "desc");
        $sparql->limit(30);






      //  $sparql->variable("?".$name);

       // $sparql->where("<".$this->violationRoot[0]->identifier.">","<".$path.">","?".$name);
    //var_dump($sparql->getQuery());die;
        $data = $sparql->launch();

      //  var_dump($data);

    //    if(!isset($this->value[$name]))
           // $this->value[$name]= array();

        $index = array();
        foreach($data["results"]["bindings"] as $result){

            if(!isset($index[$result["c0"]["value"]])){
                $index[$result["c0"]["value"]] = array("children"=>array(), "size"=>0);
            }
                $index[$result["c0"]["value"]]["children"][]=
                    array(
                        "id"=> EasyRdf_Namespace::shorten($result["c0"]["value"]). "~".$result["cm0"]["value"],
                        "name"=>EasyRdf_Namespace::shorten($result["cm0"]["value"]),
                        "value"=>intval($result["count"]["value"]));
                //$index[$result["c0"]["value"]]["value"]+=$result["count"]["value"];
                $index[$result["c0"]["value"]]["id"]=$result["c0"]["value"];
                $index[$result["c0"]["value"]]["name"]=EasyRdf_Namespace::shorten($result["c0"]["value"]);



        }



        if(isset($category)){
            $name = EasyRdf_Namespace::shorten($category);
            $id = $category;

        }

        else {
            $name = "categories";
            $id="0";
        }



        $categories = array("id" => $id,
            "name" => $name,
            "children" => array_values($index));



        return $categories;
    }

}

RDFSubject::init();