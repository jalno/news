<?php
namespace themes\clipone\Views\News\panel;
use \packages\news\Views\Panel\Comment as NewsComment;
use \packages\userpanel;
use \themes\clipone\Navigation;
use \themes\clipone\Navigation\MenuItem;
use \themes\clipone\Views\ListTrait;
use \themes\clipone\ViewTrait;
use \packages\base\Translator;

class Comment extends NewsComment{
	use ViewTrait,ListTrait;
	function __beforeLoad(){
		$this->setTitle(array(
			Translator::trans('news'),
			Translator::trans('list'),
			Translator::trans('news.unpublished')
		));
		Navigation::active("news/comments");
		$this->setButtons();
	}
	public function setButtons(){
		$this->setButton('comment_edit', $this->canEdit, array(
			'title' => Translator::trans('edit'),
			'icon' => 'fa fa-edit',
			'classes' => array('btn', 'btn-xs', 'btn-warning')
		));
		$this->setButton('comment_delete', $this->canDel, array(
			'title' => Translator::trans('delete'),
			'icon' => 'fa fa-times',
			'classes' => array('btn', 'btn-xs', 'btn-bricky')
		));
	}
}
