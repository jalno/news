<?php
namespace packages\news\Views\Panel;
use \packages\news\NewPost as Post;
use \packages\news\Views\Form;
class View extends Form{
	public function setNew(Post $new){
		$this->setData($new, 'new');
	}
	public function getNew(){
		return $this->getData('new');
	}
}
