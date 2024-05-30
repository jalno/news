<?php
namespace packages\news\Views\Panel;
use \packages\news\View;
use \packages\news\Authorization;
class Comment extends view{
	protected $canAdd;
	protected $canEdit;
	protected $canDel;
	static protected $navigation;
	function __construct(){
		$this->canAdd = Authorization::is_accessed('add');
		$this->canEdit = Authorization::is_accessed('edit');
		$this->canDel = Authorization::is_accessed('edit');
	}

	public function setComments($news){
		$this->setData($news, 'news');
	}
	public function getComments(){
		return $this->getData('news');
	}

	public static function onSourceLoad(){
		self::$navigation = Authorization::is_accessed('list');
	}
}
