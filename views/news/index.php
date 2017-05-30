<?php
namespace packages\news\views\news;
use \packages\news\views\form;
use \packages\host\views\listview as list_view;
use \packages\base\views\traits\form as formTrait;
class index extends list_view{
	use formTrait;
	public function setNews($news){
		$this->setData($news, 'news');
	}
	public function getNews(){
		return $this->getData('news');
	}
}
