<?php
namespace packages\news\views\panel;
use \packages\news\views\form;
use \packages\news\authorization;
class commentEdit extends form{

	public function setComment($comment){
		$this->setData($comment, 'comment');
	}
	public function getComment(){
		return $this->getData('comment');
	}
	public function setNews($news){
		$this->setData($news, 'news');
	}
	public function getNews(){
		return $this->getData('news');
	}
}
