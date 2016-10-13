<?php
namespace themes\clipone\views\news\panel;
use \packages\news\views\panel\published as newspublished;
use \packages\userpanel;
use \themes\clipone\navigation;
use \themes\clipone\navigation\menuItem;
use \themes\clipone\views\listTrait;
use \themes\clipone\viewTrait;
use \packages\base\translator;

class published extends newspublished{
	use viewTrait,listTrait;
	function __beforeLoad(){
		$this->setTitle(array(
			translator::trans('news'),
			translator::trans('list'),
			translator::trans('news.published')
		));
		navigation::active("news/published");
		$this->setButtons();
	}
	public static function onSourceLoad(){
		parent::onSourceLoad();
		if(parent::$navigation){
			$comments = new menuItem("comments");
			$comments->setTitle(translator::trans('news.comments'));
			$comments->setURL(userpanel\url('news/comments'));

			$unpublished = new menuItem("unpublished");
			$unpublished->setTitle(translator::trans('news.unpublished'));
			$unpublished->setURL(userpanel\url('news/unpublished'));

			$published = new menuItem("published");
			$published->setTitle(translator::trans('news.published'));
			$published->setURL(userpanel\url('news/published'));

			$item = new menuItem("news");
			$item->setTitle(translator::trans('news'));
			$item->setIcon('fa fa-envelope');
			$item->addItem($comments);
			$item->addItem($unpublished);
			$item->addItem($published);
			navigation::addItem($item);
		}
	}
	public function setButtons(){
		$this->setButton('news_view', $this->canView, array(
			'title' => translator::trans('view'),
			'icon' => 'fa fa-files-o',
			'classes' => array('btn', 'btn-xs', 'btn-green')
		));
		$this->setButton('news_edit', $this->canEdit, array(
			'title' => translator::trans('edit'),
			'icon' => 'fa fa-edit',
			'classes' => array('btn', 'btn-xs', 'btn-warning')
		));
		$this->setButton('news_delete', $this->canDel, array(
			'title' => translator::trans('news.delete'),
			'icon' => 'fa fa-times',
			'classes' => array('btn', 'btn-xs', 'btn-bricky')
		));
	}
}
