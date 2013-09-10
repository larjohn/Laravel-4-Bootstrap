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


    public static function getCategories($category=null, $depth=6)
    {
        $sparql = new SPARQL();
        $sparql->baseUrl= self::getConfig('sparqlmodel.endpoint');
        $optWheres = array();

        for($i=0;$i<$depth;$i++){

            $sp = new SPARQL();

           // echo 'lol'.$i;
            $sp->where("?cm".$i."_"."0", "<http://www.w3.org/2004/02/skos/core#broader>","?cm0");



            for($j=1; $j<$i; $j++){
                $sp->where("?cm".$i."_".$j, "<http://www.w3.org/2004/02/skos/core#broader>","?cm".$i."_".( $j-1));
            }
            $sp->where("?cm0", "<http://www.w3.org/2004/02/skos/core#broader>", "?c0");
            $sp->filterNotExists("?c0 <http://www.w3.org/2004/02/skos/core#broader> ?z");

            $sp->where("?res","<http://purl.org/dc/terms/subject>","?cm".$i."_".( $j-1));
            $sparql->union($sp);
            $optWheres[$i]= $sp;
        }

           //var_dump($sp->getQuery());





        $sparql->select(null);
        if(isset($category))
            $sparql->where("?cm0", "<http://www.w3.org/2004/02/skos/core#broader>", "<".$category.">");
        else
        {
            $sparql->where("?cm0", "<http://www.w3.org/2004/02/skos/core#broader>", "?c0");
            $sparql->filterNotExists("?c0 <http://www.w3.org/2004/02/skos/core#broader> ?z");

        }

        $sparql->optionalWhere("?res","<http://purl.org/dc/terms/subject>" ,"?cm0");
        $sparql->union($sp);

        $sparql->variable("COUNT(?res) as ?count");
        $sparql->variable("?cm0");
        $sparql->variable("?c0");
        $sparql->groupby("?c0 ?cm0");
        $sparql->having("count(?res)>65");






      //  $sparql->variable("?".$name);

       // $sparql->where("<".$this->violationRoot[0]->identifier.">","<".$path.">","?".$name);

        $data = $sparql->launch();
        //var_dump($data);

    //    if(!isset($this->value[$name]))
           // $this->value[$name]= array();

        $index = array();
        foreach($data["results"]["bindings"] as $result){
            if(isset($index[$result["c0"]["value"]])){
                $index[$result["c0"]["value"]]["children"][]=
                    array(
                        "id"=> $result["cm0"]["value"],
                        "name"=>EasyRdf_Namespace::shorten($result["cm0"]["value"]),
                        "size"=>$result["count"]["value"]);
                $index[$result["c0"]["value"]]["size"]+=$result["count"]["value"];
                $index[$result["c0"]["value"]]["id"]=$result["c0"]["value"];
                $index[$result["c0"]["value"]]["name"]=EasyRdf_Namespace::shorten($result["c0"]["value"]);

            }
            else{
                $index[$result["c0"]["value"]] = array("children"=>array(), "size"=>0);
            }
        }



        $categories = array("id" => "0",
            "name" => "categories",
            "children" => array_values($index));












       /* $categories = array(
            "id" => "0",
            "name" => "categories",
            "children" => array(
                array(
                    "id" => 5,
                    "name" => "People",

                    "children" => array(
                        array(
                            "id" => 10,
                            "name" => "Famous People",
                            "size" => 17,
                        ),
                        array(
                            "id" => 166,
                            "name" => "Singers",
                            "size" => 13,
                        )
                    )
                ),
                array(
                    "id" => 9,
                    "name" => "Institutes",
                    "children" => array(
                        array(
                            "id" => 1332,
                            "name" => "Companies",
                            "size" => 27,
                        ),
                        array(
                            "id" => 163,
                            "name" => "Universities",
                            "size" => 3,
                        )
                    )
                )
            ),


        );*/
        return $categories;
    }

}

RDFSubject::init();