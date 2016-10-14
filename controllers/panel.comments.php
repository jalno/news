<?php
namespace packages\news\controllers\panel;
use \packages\base;
use \packages\base\http;
use \packages\base\frontend\theme;
use \packages\base\NotFound;
use \packages\base\inputValidation;
use \packages\base\views\FormError;
use \packages\base\packages;


use \packages\userpanel\controller;
use \packages\userpanel;
use \packages\userpanel\user;
use \packages\userpanel\date;
use \packages\userpanel\view;

use \packages\news\newpost;
use \packages\news\comment;
use \packages\news\authorization;

class comments extends controller{
	protected $authentication = true;
	public function index($data){
		authorization::haveOrFail('comments_list');
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
	public function delete($data){
		authorization::haveOrFail('comments_delete');
		$view = view::byName("\\packages\\news\\views\\panel\\commentDelete");

		$comment = comment::byId($data['id']);
		$view->setComment($comment);
		$this->response->setStatus(false);
		if(http::is_post()){
			try {
				$comment->delete();
				$this->response->setStatus(true);
				$this->response->Go(userpanel\url('news/comments'));

			}catch(inputValidation $error){
				$view->setFormError(FormError::fromException($error));
			}
		}else{
			$this->response->setStatus(true);
		}
		$this->response->setView($view);
		return $this->response;
	}
}
