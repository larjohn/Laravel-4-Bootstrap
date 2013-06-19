<?php
/**
 * Created by JetBrains PhpStorm.
 * User: larjohns
 * Date: 19/6/2013
 * Time: 6:41 μμ

 */
use Legrand\SPARQLModel;
class RDFSubject extends SPARQLModel {
    protected static $type          = "http://purl.org/dc/terms/subject";
}

RDFSubject::init();