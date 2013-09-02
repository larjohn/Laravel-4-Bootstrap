<?php
/**
 * Created by PhpStorm.
 * User: larjohns
 * Date: 1/9/2013
 * Time: 9:37 μμ

 */
use Legrand\SPARQLModel;
use Base\RDFModel;
class RDFAccuracyConcept  extends RDFModel {

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
}

RDFAccuracyConcept::init();