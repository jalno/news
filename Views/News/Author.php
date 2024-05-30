<?php
namespace packages\news\Views\News;
use \packages\news\Views\ListView;
use \packages\base\Views\Traits\Form as FormTrait;
class Author extends ListView{
	use FormTrait;
	public function setNews(array $news){
		$this->setData($news, 'news');
	}
	public function getNews():array{
		return $this->getData('news');
	}
}
