<?php
namespace packages\news\views\news;
use \packages\news\views\listview as list_view;
use \packages\base\views\traits\form as formTrait;
class archive extends list_view{
	use formTrait;
	public function setNews(array $news){
		$this->setData($news, 'news');
	}
	public function getNews():array{
		return $this->getData('news');
	}
}
