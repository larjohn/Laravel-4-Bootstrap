<?php
/**
 * Created by JetBrains PhpStorm.
 * User: larjohns
 * Date: 24/6/2013
 * Time: 12:39 μμ

 */
namespace Base;
class RDFModel extends \Legrand\SPARQLModel {
public static  function getUriFromProperty($property){
    $class = get_called_class();
    $propertyUri = "";
    foreach($class::$mapping as $propURI=>$prop){

        if($prop ==$property){
            $propertyUri = $propURI;
            break;
        }
    }
    if($propertyUri=="")

        foreach($class::$multiMapping as $propURI=>$map){

            if($map['property'] == $property){

                $propertyUri = $propURI;
                break;
            }
        }

    return $propertyUri;
}

    public static function getType(){
        $class = get_called_class();
        return $class::$type;
    }


}