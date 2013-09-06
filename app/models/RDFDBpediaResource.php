<?php
/**
 * Created by JetBrains PhpStorm.
 * User: larjohns
 * Date: 21/6/2013
 * Time: 11:29 μμ

 */
use Legrand\SPARQLModel;
use Base\RDFModel;
class RDFDBpediaResource extends RDFModel {

    protected static $type          = "http://www.w3.org/2002/07/owl#Thing";
    protected static $status        = false;



    protected static $mapping       = [
        'http://www.w3.org/2000/01/rdf-schema#label' => 'label',

    ];

    protected static $multiMapping  = [

        "http://purl.org/dc/terms/subject" => [
            'property' => 'category',
            'mapping' => 'RDFSubject', //should be the name of the corresponding class
            'inverse' => false,
        ],
     ];
    public static  function getConfig($setting){
        switch($setting){

            case 'sparqlmodel.endpoint':{
                return 'http://dbpedia.org/sparql';
            }
            case 'sparqlmodel.graph':{
                return 'http://debug.dbpedia.org/resources';
            }
            default:{
            return Config::get($setting);
            }
        }

    }

}

RDFDBpediaResource::init();