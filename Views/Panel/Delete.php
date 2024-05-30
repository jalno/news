<?php
namespace packages\news\Views\Panel;
use \packages\news\Views\Form;
use \packages\news\Authorization;
class Delete extends Form{
	static protected $navigation;

	public function setNew($new){
		$this->setData($new, 'new');
	}
	public function getNew(){
		return $this->getData('new');
	}
}
