<?php
require_once ('libs/HTML_Template_Sigma.php');
require_once ('libs/templateManager/templateModules.php');
require_once ('libs/Configuration.php');

class page
{
	protected $tpl;
	protected $config;
	protected $metatags;
	protected $error;

	function __construct($data = null)
	{
		$data["search"] = $_REQUEST["search"];
		$this->pageData = $data;
		$this->saveData();
		$this->initTemplate();
	}

	protected function initTemplate(){
		$this->config['layoutPath'] = Configuration::templatesPath.'layout.tpl.html';
		$this->tpl = new HTML_Template_Sigma();
		$this->tpl->loadTemplateFile($this->config['layoutPath']);
		$this->tpl->setGlobalVariable('urlprefix', Configuration::getAbsolutePath());
	}

	public function parseHTMLHead()
	{
		$this->tpl->addBlockFile('HTMLHEAD','htmlHeadBlock',Configuration::templatesPath.'login/htmlhead_login.tpl.html');

		if (is_array($this->pageData['cssfiles'])){
			foreach ($this->pageData['cssfiles'] as $css){
				$this->tpl->setCurrentBlock('cssHead');
				$this->tpl->setVariable('cssfile',$css);
				$this->tpl->parse('cssHead');
			}
		}

		if (is_array($this->pageData['jsfiles'])){
			foreach ($this->pageData['jsfiles'] as $css){
				$this->tpl->setCurrentBlock('jsHead');
				$this->tpl->setVariable('jsfile',$css);
				$this->tpl->parse('jsHead');
			}
		}

		$this->tpl->touchBlock('htmlHeadBlock');
		$this->tpl->setVariable('viewpagetitle',$this->getMetas('title')!=''?$this->getMetas('title'):Configuration::DefaultTitleAdmin);
		$this->tpl->setVariable('viewmetadescription',$this->getMetas('description'));
		$this->tpl->setVariable('viewmetakeywords',$this->getMetas('keywords'));
		$this->tpl->setVariable('viewmetarobots',$this->getMetas('robot'));
		$this->tpl->setVariable('externalcss',$this->getPageDetail('external_css'));
	}
	public function parseHeader()
	{
		$this->tpl->addBlockFile('HEADER','headerBlock',Configuration::templatesPath.'login/header_login.tpl.html');
		$this->tpl->setVariable('nombre', $this->pageData["search"]["nombre"]);
		if (is_array($this->pageData["search"]["nombre"]))
			$this->tpl->parse('headerBlock');
		else
			$this->tpl->touchBlock('headerBlock');

                // Esconder o mostrar las opciones del menú principal
                // según los permisos del usuario
                require_once ('libs/caus/clsCaus.php');
                $this->tpl->setVariable('urlprefix',  Configuration::getUrlprefix());
	}

	public function parseContent()
	{
		$this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'content.tpl.html');
		$this->tpl->touchBlock('contentBlock');

	}

        public function parseFooter()
	{
		$this->tpl->addBlockFile('FOOTER','footerBlock', Configuration::templatesPath.'footer.tpl.html');
		$this->tpl->touchBlock('footerBlock');
	}

	public function displayPage()
	{
		$this->parseHTMLHead();
		$this->parseHeader();
		$this->parseContent();
                $this->parseFooter();

		if(isset($this->error)&& ($this->error['code']!='')){
			switch ($this->error['code']){
				case -1:
					header("HTTP/1.0 404 Not Found");
					return -1;
					break;
				default:
					break;
			}
		}

		$this->tpl->show();
	}

	protected function saveData(){
		if(is_array($this->pageData)&&count($this->pageData)>0){
			if(is_array($this->pageData['metaTags'])){
				$this->metatags = $this->pageData['metaTags'];
			}
			else{
				$this->metatags = array();
			}
		}
	}

	/**
	 * Get the metatag content for the specified metatagtype
	 *
	 * @param $metatagtype
	 * @return string
	 */
	protected function getMetas($metatagtype)
	{
		if(is_array($this->metatags)&& is_array($this->metatags)){
			foreach ($this->metatags as $item)
			{
				if ($item['metatagtype'] == $metatagtype)
				{
					return $item['content'];
				}
			}
		}
		return "";
	}



	/**
	 * Get the content of the module with the specified label
	 * @param $label
	 * @return array
	 */
	protected function getPageDetail($label){
		if(is_array($this->pageDetail)&& is_array($this->pageDetail)){
			foreach ($this->pageDetail as $key => $value)
			{
				if ($key == $label)
				{
					return $value;
				}
			}
		}
		return array();
	}
}
?>