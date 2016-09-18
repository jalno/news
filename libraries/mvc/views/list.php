<?php
namespace packages\news\views;
use \packages\news\view;
class listview extends view{
	protected $dataList = array();
	protected $currentPage;
	protected $totalPages;
	protected $itemsPage;
	public function setDataList($data){
		$this->dataList = $data;
	}
	public function setPaginate($currentPage, $totalPages, $itemsPage){
		$this->currentPage = $currentPage;
		$this->totalPages = $totalPages;
		$this->itemsPage = $itemsPage;
	}
}
