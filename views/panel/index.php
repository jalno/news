<?php
namespace packages\news\views\panel;
use \packages\userpanel\views\listview as list_view;
use \packages\news\authorization;
class index extends list_view{
	protected $canAdd;
	protected $canEdit;
	protected $canDel;
	static protected $navigation;
	function __construct(){
		$this->canAdd = authorization::is_accessed('add');
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
