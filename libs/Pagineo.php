<?php

class Pagineo{
	private $data;

	public function __construct($cantidad, $pagina, $limit, $tampag){
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
}
?>
