<?php
namespace themes\clipone\views\news\panel;
use \packages\news\views\panel\edit as newEdit;
use \packages\base\frontend\theme;
use \packages\userpanel;
use \themes\clipone\navigation;
use \themes\clipone\navigation\menuItem;
use \themes\clipone\views\formTrait;
use \themes\clipone\viewTrait;
use \packages\base\translator;

class edit extends newEdit{
	use viewTrait,formTrait;
	function __beforeLoad(){
		$this->setTitle(array(
			translator::trans('news'),
			translator::trans('edit'),
			"#".$this->getNew()->id
		));
		navigation::active("news");
		$this->addAssets();
	}
	protected function addAssets(){
		$this->addCSSFile(theme::url('assets/css/pages/news.edit.css'));
		$this->addJSFile(theme::url('assets/plugins/autosize/jquery.autosize.min.js'));
		$this->addJSFile(theme::url('assets/plugins/ckeditor/ckeditor.js'));
		$this->addJSFile(theme::url('assets/js/pages/new.edit.js'));
	}
	public static function onSourceLoad(){
		parent::onSourceLoad();
		if(parent::$navigation){
			$addnew = new menuItem("addnew");
			$addnew->setTitle(translator::trans('new.add'));
			$addnew->setURL(userpanel\url('news/add'));

			$comments = new menuItem("comments");
			$comments->setTitle(translator::trans('news.comments'));
			$comments->setURL(userpanel\url('news/comments'));

			$index = new menuItem("index");
			$index->setTitle(translator::trans('news'));
			$index->setURL(userpanel\url('news'));

			$item = new menuItem("news");
			$item->setTitle(translator::trans('news'));
			$item->setIcon('fa fa-envelope');
			$item->addItem($addnew);
			$item->addItem($comments);
			$item->addItem($index);
			navigation::addItem($item);
		}
	}
}
