<?php
namespace packages\news\views\news;
use \packages\news\newpost;
use \packages\news\views\form;
class archive extends form{
	public function setNews($new){
		$this->setData($new, 'new');
	}
	public function getNews(){
		return $this->getData('new');
	}
}
