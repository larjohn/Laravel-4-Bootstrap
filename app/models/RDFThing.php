<?php
/**
 * Created by JetBrains PhpStorm.
 * User: larjohns
 * Date: 19/6/2013
 * Time: 6:41 μμ

 */
use Legrand\SPARQLModel;
class RDFThing extends SPARQLModel {

    protected static $type          = "http://www.w3.org/2002/07/owl#Thing";
}

RDFThing::init();