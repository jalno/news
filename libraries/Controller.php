<?php
namespace packages\news;
use \packages\base\HTTP;
use \packages\base\DB;
use \packages\base\Response;
class Controller extends \packages\base\Controller{
	protected $response;
	protected $page = 1;
	protected $total_pages = 1;
	protected $items_per_page = 25;
	function __construct(){
		$this->page = HTTP::getURIData('page');
		$this->items_per_page = HTTP::getURIData('ipp');
		if($this->page < 1)$this->page = 1;
		if($this->items_per_page < 1)$this->items_per_page = 10;
		DB::pageLimit($this->items_per_page);
		$this->response = new Response(false);
	}
}
