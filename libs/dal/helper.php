<?php

 class DB_Helper
 {
 	public static function cleanTextLink($text){
		$text =  strtolower($text);		
		$text = preg_replace('/([\xc0-\xdf].)/se', "'&#' . ((ord(substr('$1', 0, 1)) - 192) * 64 + (ord(substr('$1', 1, 1)) - 128)) . ';'", $text);
		$text = preg_replace('/([\xe0-\xef]..)/se', "'&#' . ((ord(substr('$1', 0, 1)) - 224) * 4096 + (ord(substr('$1', 1, 1)) - 128) * 64 + (ord(substr('$1', 2, 1)) - 128)) . ';'", $text);

		$text = preg_replace("/(&#224;|&#225;|&#226;|&#227;|&#228;|&#229;|&#170;|&#192;|&#193;|&#194;|&#195;|&#196;|&#197;)/","a",$text);  		
		$text = preg_replace("/(&#203;|&#235;|&#128;|&#200;|&#201;|&#202;|&#203;|&#232;|&#233;|&#234;|&#235;)/","e",$text);
		$text = preg_replace("/(&#207;|&#237;|&#239;|&#204;|&#205;|&#206;|&#207;|&#236;|&#238;)/","i",$text);
		$text = preg_replace("/(&#246;|&#186;|&#210;|&#211;|&#212;|&#213;|&#214;|&#216;|&#240;|&#242;|&#243;|&#244;|&#245;|&#248;|&#164;)/","o",$text);  		
  		$text = preg_replace("/(&#220;|&#252;|&#181;|&#217;|&#218;|&#219;|&#220;|&#249;|&#250;|&#251;)/","u",$text);
		$text = preg_replace("/(&#223;|&#254;)/","b",$text);
  		$text = preg_replace("/(&#162;|&#169;|&#199;|&#231;)/","c",$text);
		$text = preg_replace("/(&#209;|&#241;)/","n",$text);				
		$text = preg_replace("/(&#174;)/","r",$text);
  		$text = preg_replace("/(&#138;|&#154;)/","s",$text);		
  		$text = preg_replace("/(&#129;|&#215;)/","x",$text);
  		$text = preg_replace("/(&#159;|&#221;|&#253;|&#255;)/","y",$text);
  		$text = preg_replace("/(&#142;|&#158;)/","z",$text);
  		$text = preg_replace("/(&#160;)/","",$text);
  		
		$text = preg_replace("/([^a-zA-Z0-9\-])+/","-",$text);
		$text = preg_replace("/-(-)+/","-",$text);
		$text = preg_replace("/^(-)+/","",$text);
		$text = preg_replace("/(-)+$/","",$text);
		return $text;
	}
	
 public static function getInsuranceNameFromInsuranceType($id)
 	{
 		$name = '';
 		switch ($id)
 		{
 			case '200':
 				$name = 'Individual';
 				break;
			case '210':
 				$name = 'Short Term';
 				break;
 			case '220':
 				$name = 'Group';
 				break;
 			case '230':
 				$name = 'Dental';
 				break;
 			case '240':
 				$name = 'Medicare Supplemental';
 				break;
 			case '250':
 				$name = 'Student';
 				break;
 		}
 		return $name;
	}
	
 	public static function getInsuranceTypeFromInsuranceName($name)
 	{
 		$name = strtoupper($name);
 		$id = '';

 		switch ($name)
 		{
 			case 'INDIVIDUAL':
 				$id = '200';
 				break;
			case 'SHORT-TERM':
 				$id = '210';
 				break;
 			case 'GROUP':
 				$id = '220';
 				break;
 			case 'DENTAL':
 				$id = '230';
 				break;
 			case 'MEDICARE':
 				$id = '240';
 				break;
 			case 'STUDENT':
 				$id = '250';
 				break;
 			default:
 				$id = '200';
 		}
 		return $id;
	}
	
 	public static function cleanQAText($strText)
	{
		if (preg_match('/.*\s*.*<span class="question">(.*)<\/span>(.*\s*.*)<span class="answer">(.*)<\/span>((.*\s*.*)*)/',$strText,$coin))
		{
			for ($i = 1; $i < count($coin); $i++) {
				switch($i)
				{
					case 1:
						$result .= '<strong>'. $coin[$i] . ' </strong>';
						break;
					case 2:
						$result .= strip_tags($coin[$i]);
						break;
					case 3:
						$result .= '<br /><strong>'. $coin[$i] . ' </strong>';
						break;
					case 4:
						$result .= strip_tags($coin[$i]);
						break;
				}
			}
		} else if (preg_match('/.*\s*.*Return To:.*((.*\s*.*)*)/',$strText,$coin)) {
				$result = strip_tags($coin[1]);
		} else {
			$result = strip_tags($strText);
		}

		$cleanAnswerTag = str_replace('[AnswerTag]','',$result);
		$cleanSearchBoxTag = str_replace('[searchbox]','',$cleanAnswerTag);

		return $cleanSearchBoxTag;
	}

	public static function cleanQAComment($strText)
	{
		return strip_tags($strText);
	}
	
	public static function isValidStateName($stateName) 
	{
		$stateName = str_replace("-"," ",strtoupper($stateName));
		switch ($stateName)
		{
			case "ALABAMA":
            case "ALASKA":
            case "ARIZONA":
            case "ARKANSAS":
            case "CALIFORNIA":
            case "COLORADO":
            case "CONNECTICUT":
            case "DELAWARE":
            case "DISTRICT OF COLUMBIA":
            case "FLORIDA":
            case "GEORGIA":
            case "HAWAII":
            case "IDAHO":
            case "ILLINOIS":
            case "INDIANA":
            case "IOWA":
            case "KANSAS":
            case "KENTUCKY":
            case "LOUISIANA":
            case "MAINE":
            case "MASSACHUSETTS":
            case "MARYLAND":
            case "MICHIGAN":
            case "MINNESOTA":
            case "MISSISSIPPI":
            case "MISSOURI":
            case "MONTANA":
            case "NEBRASKA":
            case "NEVADA":
            case "NEW HAMPSHIRE":
            case "NEW JERSEY":
            case "NEW MEXICO":
            case "NEW YORK":
            case "NORTH CAROLINA":
            case "NORTH DAKOTA":
            case "OHIO":
            case "OKLAHOMA":
            case "OREGON":
            case "PENNSYLVANIA":
            case "RHODE ISLAND":
            case "SOUTH CAROLINA":
            case "SOUTH DAKOTA":
            case "TENNESSEE":
            case "TEXAS":
            case "UTAH":
            case "VIRGINIA":
            case "VIRGIN ISLANDS":
            case "VERMONT":
            case "WASHINGTON":
            case "WEST VIRGINIA":
            case "WISCONSIN":
            case "WYOMING":
            	$result = true;
            	break;
            default: 
            	$result = false;
            	break;
		}
		return $result;
	}
 }
?>