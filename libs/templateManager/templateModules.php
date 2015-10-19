<?php

require_once 'libs/dal/helper.php';

/**
 * @version 1.0
 * @created 16-Jun-2009 04:59:06 p.m.
 */
class templateModules
{
	/*public static function parseRightSearchBox($tpl, $data, $tplFileName = 'rightSearchBox.tpl.html')
	{
		$tplPath = Configuration::templatesPath . $tplFileName;
		$tpl->addBlockFile('RIGHTSEARCHBOX','rightSearchBoxBlock',$tplPath);
		$tpl->touchBlock('rightSearchBoxBlock');
		foreach ($data['content']['insuranceTypes'] as $item)
		{
			$tpl->setCurrentBlock('rightTypeOfInsurance_block');
			$tpl->setVariable('righttypeofinsurance_value',$item['id']);
			$tpl->setVariable('righttypeofinsurance_content',$item['name']);
			$tpl->parseCurrentBlock('rightTypeOfInsurance_block');
		}
	}*/
}
?>