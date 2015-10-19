<?php
class TemplateHelp{

	function __construct()
	{
	}

	public static function getPaginator($tpl,$config){
		$tpl->addBlockFile('viewPaginator','viewPaginator',Configuration::templatesPath.'templatePaginator.tpl.html');

		if (is_array($config['pagineo']['pagesEnabled']) && count($config['pagineo']['pagesEnabled']) > 1){
			$tpl->touchBlock("pagineo_block");
			foreach ($config['pagineo']['pagesEnabled'] as $page){
				if ($config['page'] == $page){
					$tpl->setCurrentBlock("current_page_block");
					$tpl->setVariable('page',$page);
					$tpl->parse("current_page_block");
				}else{
					$tpl->setCurrentBlock("page_block");
					$tpl->setVariable('page_link','&pagina='.$page);
					$tpl->setVariable('page',$page);
					$tpl->setVariable('class','actual');
					$tpl->parse("page_block");
				}
			}

			if ($config['pagineo']['prevPage'] > 0){
				$tpl->setCurrentBlock("prev_block");
				$tpl->setVariable('prev','&pagina='.$config['pagineo']['prevPage']);
				$tpl->parse("prev_block");
			}

			if ($config['pagineo']['nextPage'] > 0){
				$tpl->setCurrentBlock("next_block");
				$tpl->setVariable('next','&pagina='.$config['pagineo']['nextPage']);
				$tpl->parse("next_block");
			}

			$tpl->setCurrentBlock("first_block");
			$tpl->setVariable('first','&pagina=1');
			$tpl->parse("first_block");

			$tpl->setCurrentBlock("last_block");
			$tpl->setVariable('last','&pagina='.$config['pagineo']['totalPages']);
			$tpl->parse("last_block");
		}

		$tpl->parse("viewPaginator");
		return $tpl;
	}

        public static function showActionButtons($tpl,$config){
            if ($config["acciones"]["modificar"]){
                $tpl->setCurrentBlock("blkOpcionModificar");
                $tpl->setVariable($config);
                $tpl->parse("blkOpcionModificar");
            }else{
                $tpl->hideBlock("blkOpcionModificar");
            }
            if ($config["acciones"]["borrar"]){
                $tpl->setCurrentBlock("blkOpcionBorrar");
                $tpl->setVariable($config);
                $tpl->parse("blkOpcionBorrar");
            }else{
                $tpl->hideBlock("blkOpcionBorrar");
            }

            return $tpl;
        }

        public static function showButtons($tpl,$config){
            if ($config["acciones"]["agregar"])
                $tpl->touchBlock("blkAgregar");
            if ($config["acciones"]["reportes"])
                $tpl->touchBlock("blkReportes");
            if ($config["acciones"]["modificar"]){
                $tpl->touchBlock("blkHeaderModificar");
            }
            if ($config["acciones"]["borrar"]){
                $tpl->touchBlock("blkHeaderBorrar");
            }

            return $tpl;
        }

	public static function getSearchParameters($url,$config){
		$param = '';
		if (is_array($config)){
			foreach ($config as $key => $search){
				$extra[] = 'search['.$key.']='.$search;
			}
			if (is_array($extra)) $param = implode("&",$extra);
		}

		return $url.$param;
	}
}
?>