<?php
/**
 * @author Omer Hassan
 */
class DOMv2
{
    
    function principal($data)
    { 
        // initializing or creating array
        $template_info =  $data;;

        // creating object of SimpleXMLElement
        $xml_template_info = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><root></root>");

        // function call to convert array to xml
        self::array_to_xml($template_info,$xml_template_info);

        // Imprimir el xml

        return $xml_template_info->asXML() ;
    }
    
    function array_to_xml($template_info, &$xml_template_info) {
            foreach($template_info as $key => $value) {
                if(is_array($value)) {
                    if(!is_numeric($key)){
 
                        if ($key =="") {
                            $subnode = $xml_template_info->addChild("nn");
                        } else{
                            $subnode = $xml_template_info->addChild("$key");
                        }
 
                        if(count($value) >1 && is_array($value)){
                            $jump = false;
                            $count = 1;
                            foreach($value as $k => $v) {
                                if(is_array($v)){
                                    if($count++ > 1)
                                        $subnode = $xml_template_info->addChild("$key");
 
                                    self::array_to_xml($v, $subnode);
                                    $jump = true;
                                }
                            }
                            if($jump) {
//                                goto LE;
                            }
                            self::array_to_xml($value, $subnode);
                        }
                        else
                            self::array_to_xml($value, $subnode);
                    }
                    else{
                        self::array_to_xml($value, $xml_template_info);
                    }
                }
                else {
                    
                    if (!is_numeric($key)){
                        $xml_template_info->addChild("$key","$value");
                    } else
                    {
                        $xml_template_info->addChild("n"."$key","$value");
                    }
                }
 
//                LE: ;
            }
        }
 
}