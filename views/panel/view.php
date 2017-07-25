<?php
namespace packages\news\views\panel;
use \packages\news\newpost as post;
use \packages\news\views\form;
class view extends form{
	public function setNew(post $new){
		$this->setData($new, 'new');
	}
	public function getNew(){
		return $this->getData('new');
	}
}
