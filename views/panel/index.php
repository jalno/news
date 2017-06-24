<?php
namespace packages\news\views\panel;
use \packages\userpanel\views\listview;
use \packages\news\authorization;
use \packages\base\views\traits\form as formTrait;
class index extends listview{
	use formTrait;
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
		$this->setDataList($news);
	}
	public function getNews(){
		return $this->getDataList();
	}

	public static function onSourceLoad(){
		self::$navigation = authorization::is_accessed('list');
	}
}
