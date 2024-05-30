<?php
namespace themes\clipone\Views\News\Panel;
use \packages\base\Frontend\Theme;
use \packages\userpanel;
use \themes\clipone\Navigation;
use \themes\clipone\Navigation\MenuItem;
use \themes\clipone\Views\FormTrait;
use \themes\clipone\ViewTrait;
use \packages\base\Translator;

class CommentEdit extends \packages\news\Views\Panel\CommentEdit{
	use ViewTrait,FormTrait;
	function __beforeLoad(){
		$this->setTitle(array(
			Translator::trans('comment'),
			Translator::trans('edit')
		));
		Navigation::active("news/comments");
		$this->addAssets();
	}
	protected function addAssets(){
		$this->addJSFile(Theme::url('assets/plugins/ckeditor/ckeditor.js'));
	}
	protected function setNewsForSelect(){
		$news = array();
		foreach($this->getNews() as $new){
			$news[] = array(
				'title' => $new->title,
				'value' => $new->id
			);
		}
		return $news;
	}
}
