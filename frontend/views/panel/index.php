<?php
namespace themes\clipone\views\news\panel;
use \packages\news\views\panel\index as newsIndex;
use \packages\userpanel;
use \themes\clipone\navigation;
use \themes\clipone\navigation\menuItem;
use \themes\clipone\views\listTrait;
use \themes\clipone\views\formTrait;
use \themes\clipone\viewTrait;
use \packages\base\translator;
use \packages\base\view\error;
class index extends newsIndex{
	use viewTrait, listTrait, formTrait;
	function __beforeLoad(){
		$this->setTitle([
			translator::trans('list'),
			translator::trans('news')
		]);
		navigation::active("news/index");
		$this->setButtons();
		if(empty($this->getNews())){
			$this->addNotFoundError();
		}
	}
	private function addNotFoundError(){
		$error = new error();
		$error->setType(error::NOTICE);
		$error->setCode('news.post.notfound');
		if($this->canAdd){
			$error->setData([
				[
					'type' => 'btn-success',
					'txt' => translator::trans('news.post.add'),
					'link' => userpanel\url('news/add')
				]
			], 'btns');
		}
		$this->addError($error);
	}
	public static function onSourceLoad(){
		parent::onSourceLoad();
		if(parent::$navigation){
			$addnew = new menuItem("addnew");
			$addnew->setTitle(translator::trans('new.add'));
			$addnew->setIcon('fa fa-plus');
			$addnew->setURL(userpanel\url('news/add'));

			$comments = new menuItem("comments");
			$comments->setTitle(translator::trans('news.comments'));
			$comments->setIcon('fa fa-comments-o');
			$comments->setURL(userpanel\url('news/comments'));

			$index = new menuItem("index");
			$index->setTitle(translator::trans('news'));
			$index->setIcon('fa fa-newspaper-o');
			$index->setURL(userpanel\url('news'));

			$item = new menuItem("news");
			$item->setTitle(translator::trans('news'));
			$item->setIcon('fa fa-newspaper-o');
			$item->addItem($index);
			$item->addItem($addnew);
			$item->addItem($comments);
			navigation::addItem($item);
		}
	}
	public function setButtons(){
		$this->setButton('news_edit', $this->canEdit, [
			'title' => translator::trans('edit'),
			'icon' => 'fa fa-edit',
			'classes' => ['btn', 'btn-xs', 'btn-teal']
		]);
		$this->setButton('news_delete', $this->canDel, [
			'title' => translator::trans('delete'),
			'icon' => 'fa fa-times',
			'classes' => ['btn', 'btn-xs', 'btn-bricky']
		]);
	}
	public function getComparisonsForSelect(){
		return [
			[
				'title' => translator::trans('search.comparison.contains'),
				'value' => 'contains'
			],
			[
				'title' => translator::trans('search.comparison.equals'),
				'value' => 'equals'
			],
			[
				'title' => translator::trans('search.comparison.startswith'),
				'value' => 'startswith'
			]
		];
	}
}
