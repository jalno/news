<?php
namespace themes\clipone\views\news\panel;
use \packages\base\frontend\theme;
use \packages\userpanel;
use \themes\clipone\navigation;
use \themes\clipone\navigation\menuItem;
use \themes\clipone\views\formTrait;
use \themes\clipone\viewTrait;
use \packages\base\translator;

class commentEdit extends \packages\news\views\panel\commentEdit{
	use viewTrait,formTrait;
	function __beforeLoad(){
		$this->setTitle(array(
			translator::trans('comment'),
			translator::trans('edit')
		));
		navigation::active("news/comments");
		$this->addAssets();
	}
	protected function addAssets(){
		$this->addJSFile(theme::url('assets/plugins/ckeditor/ckeditor.js'));
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
