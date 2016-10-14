<?php
namespace packages\news\views\panel;
use \packages\news\view;
use \packages\news\authorization;
class comment extends view{
	protected $canAdd;
	protected $canEdit;
	protected $canDel;
	static protected $navigation;
	function __construct(){
		$this->canAdd = authorization::is_accessed('add');
		$this->canEdit = authorization::is_accessed('edit');
		$this->canDel = authorization::is_accessed('edit');
	}

	public function setComments($news){
		$this->setData($news, 'news');
	}
	public function getComments(){
		return $this->getData('news');
	}

	public static function onSourceLoad(){
		self::$navigation = authorization::is_accessed('list');
	}
}
