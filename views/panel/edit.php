<?php
namespace packages\news\views\panel;
use \packages\news\views\form;
use \packages\news\authorization;
class edit extends form{
	static protected $navigation;

	public function setNew($new){
		$this->setData($new, 'new');
	}
	public function getNew(){
		return $this->getData('new');
	}

	public static function onSourceLoad(){
		self::$navigation = authorization::is_accessed('list');
	}
}
