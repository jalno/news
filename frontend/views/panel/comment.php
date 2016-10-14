<?php
namespace themes\clipone\views\news\panel;
use \packages\news\views\panel\comment as newscomment;
use \packages\userpanel;
use \themes\clipone\navigation;
use \themes\clipone\navigation\menuItem;
use \themes\clipone\views\listTrait;
use \themes\clipone\viewTrait;
use \packages\base\translator;

class comment extends newscomment{
	use viewTrait,listTrait;
	function __beforeLoad(){
		$this->setTitle(array(
			translator::trans('news'),
			translator::trans('list'),
			translator::trans('news.unpublished')
		));
		navigation::active("news/comments");
		$this->setButtons();
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
	public function setButtons(){
		$this->setButton('comment_edit', $this->canEdit, array(
			'title' => translator::trans('edit'),
			'icon' => 'fa fa-edit',
			'classes' => array('btn', 'btn-xs', 'btn-warning')
		));
		$this->setButton('comment_delete', $this->canDel, array(
			'title' => translator::trans('delete'),
			'icon' => 'fa fa-times',
			'classes' => array('btn', 'btn-xs', 'btn-bricky')
		));
	}
}
