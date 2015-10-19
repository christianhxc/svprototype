<?php
/**
 * @author Omer Hassan
 */
class DOM
{
  const ATTRIBUTES = '__attributes__';
  const CONTENT = '__content__';

  /**
   * @param array $source
   * This source array:
   *
   * <code>
   * Array
   * (
   *   [book] => Array
   *     (
   *       [0] => Array
   *         (
   *           [author] => Author0
   *           [title] => Title0
   *           [publisher] => Publisher0
   *           [__attributes__] => Array
   *             (
   *               [isbn] => 978-3-16-148410-0
   *             )
   *         )
   *       [1] => Array
   *         (
   *           [author] => Array
   *             (
   *               [0] => Author1
   *               [1] => Author2
   *             )
   *           [title] => Title1
   *           [publisher] => Publisher1
   *         )
   *       [2] => Array
   *         (
   *           [__attributes__] => Array
   *             (
   *               [isbn] => 978-3-16-148410-0
   *             )
   *           [__content__] => Title2
   *         )
   *     )
   * )
   * </code>
   *
   * will produce this XML:
   *
   * <code>
   * <root>
   *   <book isbn="978-3-16-148410-0">
   *     <author>Author0</author>
   *     <title>Title0</title>
   *     <publisher>Publisher0</publisher>
   *   </book>
   *   <book>
   *     <author>Author1</author>
   *     <author>Author2</author>
   *     <title>Title1</title>
   *     <publisher>Publisher1</publisher>
   *   </book>
   *   <book isbn="978-3-16-148410-0">Title2</book>
   * </root>
   * </code>
   * @param string $rootTagName
   * @return DOMDocument
   */
  public static function arrayToDOMDocument(array $source, $rootTagName = 'root')
  {
    $document = new DOMDocument('1.0', 'UTF-8');
     
    $element = $document->createElement($rootTagName);
    $document->appendChild( self::createDOMElement($source, $rootTagName, $element, $document));
//    $document->appendChild($element);
//    self::createDOMElement($source, $rootTagName, $document
//    $element->appendChild(self::createDOMElement($source, $rootTagName, $document));
//    $document->appendChild($element);
    return $document;
  }

  /**
   * @param array $source
   * @param string $rootTagName
   * @param bool $formatOutput
   * @return string
   */
  public static function arrayToXMLString(array $source, $rootTagName = 'root', $formatOutput = true)
  {
    $document = self::arrayToDOMDocument($source, $rootTagName);
    $document->formatOutput = $formatOutput;

//	$nodexml = $document->childNodes->item(0);
	
    return $document->saveXML();
  }

  /**
   * @param DOMDocument $document
   * @return array
   */
  public static function domDocumentToArray(DOMDocument $document)
  {
    return self::createArray($document->documentElement);
  }

  /**
   * @param string $xmlString
   * @return array
   */
  public static function xmlStringToArray($xmlString)
  {
    $document = new DOMDocument();
    
    return $document->loadXML($xmlString) ? self::domDocumentToArray($document) : array();
  }

  /**
   * @param mixed $source
   * @param string $tagName
   * @param DOMDocument $document
   * @return DOMNode
   */
  private static function createDOMElement($source, $tagName, $elem_root, DOMDocument $document)
  {
    $element = $elem_root;
    if (!is_array($source))
    {  
      $single_node = $document->createElement($tagName,$source);
      return $single_node;
    }else
    {
        foreach ((is_array($source) ? $source : array($source)) as $elementKey => $elementValue){
            if (!is_array($elementValue)){
                // Si el valor no es array
                $elementValue = replaceCharacter($elementValue);
                $element->appendChild(self::createDOMElement($elementValue, (is_numeric($elementKey) ? $tagName : $elementKey), $elem_root, $document));      
            } else
            {   // Si el valor es array sigue recursivamente hasta llegar al hijo menor, como en la teoría de grafos.
                $dummy_node = $document->createElement((is_numeric($elementKey) ? $tagName : $elementKey));
                $element->appendChild(self::createDOMElement($elementValue, (is_numeric($elementKey) ? $tagName : $elementKey), $dummy_node, $document));                                                            
            }

        }          
    }
      // regresa el XML armado correctamente
    return $element;
  }

  /**
   * @param DOMNode $domNode
   * @return array
   */
  private static function createArray(DOMNode $domNode)
  {
    $array = array();

    for ($i = 0; $i < $domNode->childNodes->length; $i++)
    {
      $item = $domNode->childNodes->item($i);

      if ($item->nodeType == XML_ELEMENT_NODE)
      {
        $arrayElement = array();

        for ($attributeIndex = 0; !is_null($attribute = $item->attributes->item($attributeIndex)); $attributeIndex++)
          if ($attribute->nodeType == XML_ATTRIBUTE_NODE)
            $arrayElement[self::ATTRIBUTES][$attribute->nodeName] = $attribute->nodeValue;

        $children = self::createArray($item);

        if (is_array($children))
          $arrayElement = array_merge($arrayElement, $children);
        else
          $arrayElement[self::CONTENT] = $children;

        $array[$item->nodeName][] = $arrayElement;
      }
      else if ($item->nodeType == XML_CDATA_SECTION_NODE || ($item->nodeType == XML_TEXT_NODE && trim($item->nodeValue) != ''))
        return $item->nodeValue;
    }

    return $array;
  }
  
  private static function replaceCharacter($text){
        $contenido= $text;
        $contenido=str_replace("á","a",$contenido);
        $contenido=str_replace("é","e",$contenido);
        $contenido=str_replace("í","i",$contenido);
        $contenido=str_replace("ó","o",$contenido);
        $contenido=str_replace("ú","u",$contenido);
        
        $contenido=str_replace("[ÁÀÂÃ]","A",$contenido);
        $contenido=str_replace("[ÉÈÊ]","E",$contenido);
        $contenido=str_replace("[ÍÌÎ]","I",$contenido);
        $contenido=str_replace("[ÓÒÔÕ]","O",$contenido);
        $contenido=str_replace("[ÚÙÛ]","U",$contenido);
        
        $contenido=str_replace("ä","a",$contenido);
        $contenido=str_replace("ë","e",$contenido);
        $contenido=str_replace("ï","i",$contenido);
        $contenido=str_replace("ö","o",$contenido);
        $contenido=str_replace("ü","u",$contenido);
        
        $contenido=str_replace("ñ", "n", $contenido);
        $contenido=str_replace("Ñ", "N", $contenido);
        return $contenido;
    }
    
}