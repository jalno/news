<?php
namespace packages\news\views\panel;
use \packages\news\views\form;
use \packages\news\authorization;
class commentDelete extends form{

	public function setComment($comment){
		$this->setData($comment, 'comment');
	}
	public function getComment(){
		return $this->getData('comment');
	}
}
