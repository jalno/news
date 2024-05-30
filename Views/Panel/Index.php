<?php
namespace packages\news\Views\Panel;
use \packages\userpanel\Views\ListView;
use \packages\news\Authorization;
use \packages\base\Views\Traits\Form as FormTrait;
class Index extends ListView{
	use FormTrait;
	protected $canAdd;
	protected $canEdit;
	protected $canDel;
	static protected $navigation;
	function __construct(){
		$this->canAdd = Authorization::is_accessed('add');
		$this->canEdit = Authorization::is_accessed('edit');
		$this->canDel = Authorization::is_accessed('edit');
	}

	public function setNews($news){
		$this->setDataList($news);
	}
	public function getNews(){
		return $this->getDataList();
	}

	public static function onSourceLoad(){
		self::$navigation = Authorization::is_accessed('list');
	}
}
