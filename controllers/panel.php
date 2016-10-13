<?php
namespace packages\news\controllers\panel;
use \packages\base;
use \packages\base\http;
use \packages\base\NotFound;
use \packages\base\inputValidation;
use \packages\base\views\FormError;

use \packages\userpanel\controller;
use \packages\userpanel\view;
use \packages\news\newpost;
use \packages\news\comment;
use \packages\news\authorization;

use \packages\userpanel\date;

class news extends controller{
	protected $authentication = true;
	public function published(){
		authorization::haveOrFail('list_view');
		$view = view::byName("\\packages\\news\\views\\panel\\published");
		$new = new newpost();
		$new->orderBy('date', 'DESC');
		$new->where('status', newpost::published);
		$view->setNews($new->get());
		$this->response->setView($view);
		return $this->response;
	}
	public function unpublished(){
		authorization::haveOrFail('list_view');
		$view = view::byName("\\packages\\news\\views\\panel\\unpublished");
		$new = new newpost();
		$new->orderBy('id', 'ASC');
		$new->where('status', newpost::unpublished);
		$view->setNews($new->get());
		$this->response->setView($view);
		return $this->response;
	}
	public function comments($data){
		authorization::haveOrFail('list_view');
		$view = view::byName("\\packages\\news\\views\\panel\\comment");
		$comment = new comment;
		if($data['id']){
			$comment->where('post', $data['id']);
		}
		$comment->orderBy('date', 'DESC');
		$view->setComments($comment->get());
		$this->response->setView($view);
		return $this->response;
	}
}
