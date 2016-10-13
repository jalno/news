<?php
namespace packages\news\views\panel;
use \packages\news\view;
use \packages\news\authorization;
class unpublished extends view{
	protected $canAdd;
	protected $canView;
	protected $canEdit;
	protected $canDel;
	static protected $navigation;
	function __construct(){
		$this->canAdd = authorization::is_accessed('add');
		$this->canView = authorization::is_accessed('view');
		$this->canEdit = authorization::is_accessed('edit');
		$this->canDel = authorization::is_accessed('edit');
	}

	public function setNews($news){
		$this->setData($news, 'news');
	}
	public function getNews(){
		return $this->getData('news');
	}

	public static function onSourceLoad(){
		self::$navigation = authorization::is_accessed('list');
	}
}
