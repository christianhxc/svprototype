<?php
require_once("libs/Configuration.php");

class PagineoAjax {
    private $data;
    private $template;
    private $callback;
    private $callbackImg;

    public function __construct($cantidad, $pagina, $limit, $tampag){
        // HTML Template del pagineo
        $this->callback = "<a href='#' onclick='refrescarResultados([number]);'>[number]</a> ";
        $this->callbackImg = "<a href='#' onclick='refrescarResultados([number]);'>[imagen]</a> ";

        $this->template .= '<div class="pageinate">';

        $template = str_replace("[number]", "[first]", $this->callbackImg);
        $this->template .= str_replace("[imagen]",
                '<img alt="Primera" title="Primera" src="'.Configuration::getUrlprefix().'img/paginado/principio.png" border="0px" />',
                $template);

        $template = str_replace("[number]", "[prev]", $this->callbackImg);
        $this->template .= str_replace("[imagen]",
                '<img alt="Anterior" title="Anterior" src="'.Configuration::getUrlprefix().'img/paginado/atras.png" border="0px" />',
                $template);


        $this->template .= "[page]";

        $template = str_replace("[number]", "[next]", $this->callbackImg);
        $this->template .= str_replace("[imagen]",
                '<img alt="Siguiente" title="Siguiente" src="'.Configuration::getUrlprefix().'img/paginado/adelante.png" border="0px" />',
                $template);

        $template = str_replace("[number]", "[last]", $this->callbackImg);
        $this->template .= str_replace("[imagen]",
                '<img alt="Ultima" title="Ultima" src="'.Configuration::getUrlprefix().'img/paginado/final.png" border="0px" />',
                $template);

        $this->template .= '</div>';

        $this->data['offset'] = isset($this->data['offset']) ? $this->data['offset'] : 10;
        $this->data['page'] = (isset($pagina) && ($pagina>0) && is_numeric($pagina)) ? $pagina : 1;
        $this->data['limit'] = (isset($limit) && is_numeric($limit) && $limit > 0) ? $limit : 10;
        $this->data['countPagesEnabled'] = (isset($tampag) && is_numeric($tampag) && $tampag > 0) ? $tampag : 10;
        $this->data['countPagesEnabledPerSide'] = floor($this->data['countPagesEnabled'] / 2);
        $this->data['totalPages'] = ceil($cantidad / $this->data['limit']);

        if (($this->data['totalPages'] > 0) && ($this->data['totalPages'] < 1))
                $this->data['totalPages'] = 1;
        $this->data['totalPages'] = (!is_int($this->data['totalPages'])) ? floor($this->data['totalPages']) : $this->data['totalPages'];

        if ($this->data['page'] > $this->data['totalPages'])
                $this->data['page'] = $this->data['totalPages'];

        $this->data['currentPage'] = $this->data['page'];
        $this->data['nextPage'] = ($this->data['page'] > 0 && ($this->data['page'] ) < $this->data['totalPages']) ? $this->data['page'] + 1 : -1;
        $this->data['prevPage'] = ($this->data['page'] > 1) ? $this->data['page'] -1 : -1 ;

        $firstPage = ($this->data['prevPage'] - $this->data['countPagesEnabledPerSide'] < 1 ) ? 1 : $this->data['currentPage'] - $this->data['countPagesEnabledPerSide'];

        if ($this->data['currentPage'] <= $this->data['countPagesEnabledPerSide'])
                $lastPage = $this->data['countPagesEnabled'];
        else
                $lastPage = $this->data['currentPage'] + $this->data['countPagesEnabledPerSide'];

        $firstPage = (($this->data['currentPage']) >= $this->data['totalPages'] - $this->data['countPagesEnabledPerSide']) ? $this->data['totalPages'] - $this->data['countPagesEnabled'] : $firstPage;
        $lastPage = ($this->data['currentPage'] + $this->data['countPagesEnabledPerSide'] > $this->data['totalPages']) ? $this->data['totalPages'] : $lastPage;

        $firstPage = ($firstPage < 0) ? 1 : $firstPage;

        for ($i = $firstPage; $i<=$lastPage;$i++)
                $this->data['pagesEnabled'][] = $i;
    }

    public function getPagineo(){
        return $this->data;
    }

    public function renderPagineo(){
        if (is_array($this->data['pagesEnabled']) && count($this->data['pagesEnabled']) > 1){
            $this->template = str_replace("[first]", 1, $this->template);
            $this->template = str_replace("[prev]", $this->data['prevPage'] > 0 ? $this->data['prevPage'] : "", $this->template);
            //echo "<pre>"; print_r($this->data); echo "</pre>";

            $paginas = "";
            foreach ($this->data['pagesEnabled'] as $page){
                if ($this->data['page'] == $page){
                    $paginas .= "[".$page."] ";
                }else{
                    $paginas .= str_replace("[number]", $page, $this->callback);
                }
            }

            $this->template = str_replace("[page]", $paginas, $this->template);
            $this->template = str_replace("[next]", $this->data['nextPage'] > 0 ? $this->data['nextPage'] : "", $this->template);
            $this->template = str_replace("[last]", $this->data['totalPages'], $this->template);

            // Imprimir el HTML ya con los datos
            echo $this->template;
        }
    }
}
?>



