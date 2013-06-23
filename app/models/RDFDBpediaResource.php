<?php
/**
 * Created by JetBrains PhpStorm.
 * User: larjohns
 * Date: 21/6/2013
 * Time: 11:29 μμ

 */
use Legrand\SPARQLModel;
class RDFDBpediaResource extends SPARQLModel {

    protected static $type          = "http://www.w3.org/2002/07/owl#Thing";
    protected static $status        = false;
    public static  function getConfig($setting){
        switch($setting){

            case 'sparqlmodel.endpoint':{
                 return 'http://dbpedia.org/sparql';

            }
            case 'sparqlmodel.graph':{
                return 'http://dbpedia.org';

            }
            default:{
                return Config::get($setting);
            }


        }

    }


    protected static $mapping       = [
        'http://www.w3.org/2000/01/rdf-schema#label' => 'label',

    ];


}

RDFDBpediaResource::init();