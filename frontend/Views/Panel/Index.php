<?php
namespace themes\clipone\Views\News\Panel;
use \packages\news\Views\Panel\Index as NewsIndex;
use \packages\userpanel;
use \themes\clipone\Navigation;
use \themes\clipone\Navigation\MenuItem;
use \themes\clipone\Views\ListTrait;
use \themes\clipone\Views\FormTrait;
use \themes\clipone\ViewTrait;
use \packages\base\Translator;
use \packages\base\View\Error;
class Index extends NewsIndex{
	use ViewTrait, ListTrait, FormTrait;
	function __beforeLoad(){
		$this->setTitle([
			Translator::trans('list'),
			Translator::trans('news')
		]);
		Navigation::active("news/index");
		$this->setButtons();
		if(empty($this->getNews())){
			$this->addNotFoundError();
		}
	}
	private function addNotFoundError(){
		$error = new Error();
		$error->setType(Error::NOTICE);
		$error->setCode('news.post.notfound');
		if($this->canAdd){
			$error->setData([
				[
					'type' => 'btn-success',
					'txt' => Translator::trans('news.post.add'),
					'link' => userpanel\url('news/add')
				]
			], 'btns');
		}
		$this->addError($error);
	}
	public static function onSourceLoad(){
		parent::onSourceLoad();
		if(parent::$navigation){
			$addnew = new MenuItem("addnew");
			$addnew->setTitle(Translator::trans('new.add'));
			$addnew->setIcon('fa fa-plus');
			$addnew->setURL(userpanel\url('news/add'));

			$comments = new MenuItem("comments");
			$comments->setTitle(Translator::trans('news.comments'));
			$comments->setIcon('fa fa-comments-o');
			$comments->setURL(userpanel\url('news/comments'));

			$index = new MenuItem("index");
			$index->setTitle(Translator::trans('news'));
			$index->setIcon('fa fa-newspaper-o');
			$index->setURL(userpanel\url('news'));

			$item = new MenuItem("news");
			$item->setTitle(Translator::trans('news'));
			$item->setIcon('fa fa-newspaper-o');
			$item->addItem($index);
			$item->addItem($addnew);
			$item->addItem($comments);
			Navigation::addItem($item);
		}
	}
	public function setButtons(){
		$this->setButton('news_edit', $this->canEdit, [
			'title' => Translator::trans('edit'),
			'icon' => 'fa fa-edit',
			'classes' => ['btn', 'btn-xs', 'btn-teal']
		]);
		$this->setButton('news_delete', $this->canDel, [
			'title' => Translator::trans('delete'),
			'icon' => 'fa fa-times',
			'classes' => ['btn', 'btn-xs', 'btn-bricky']
		]);
	}
	public function getComparisonsForSelect(){
		return [
			[
				'title' => Translator::trans('search.comparison.contains'),
				'value' => 'contains'
			],
			[
				'title' => Translator::trans('search.comparison.equals'),
				'value' => 'equals'
			],
			[
				'title' => Translator::trans('search.comparison.startswith'),
				'value' => 'startswith'
			]
		];
	}
}
