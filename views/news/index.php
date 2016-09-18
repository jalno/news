<?php
namespace packages\news\views\news;
use \packages\news\views\form;
class index extends form{
	public function setNews($news){
		$this->setData($news, 'news');
	}
	public function getNews(){
		return $this->getData('news');
	}
}
