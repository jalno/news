<?php
namespace packages\news\views\panel;
use \packages\news\newpost;
use \packages\news\views\form;
class view extends form{
	public function setNew(newpost $new){
		$this->setData($new, 'new');
	}
	public function getNew(){
		return $this->getData('new');
	}
}
