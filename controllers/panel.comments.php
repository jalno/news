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
	public function edit($data){
		authorization::haveOrFail('comments_edit');
		$view = view::byName("\\packages\\news\\views\\panel\\commentEdit");

		$comment = comment::byId($data['id']);
		$view->setComment($comment);
		$inputsRules = array(
			'name' => array(
				'type' => 'string',
				'optional' => true
			),
			'email' => array(
				'type' => 'email',
				'optional' => true
			),
			'text' => array(
				'optional' => true
			),
			'date' => array(
				'type' => 'date',
				'optional' => true
			),
			'status' => array(
				'type' => 'number',
				'optional' => true,
				'values' => array(comment::accepted, comment::pending, comment::unverified)
			)
		);
		$this->response->setStatus(false);
		if(http::is_post()){
			try {
				$inputs = $this->checkinputs($inputsRules);
				$inputs['date'] = date::strtotime($inputs['date']);
				if($inputs['date'] <= 0){
					throw new inputValidation("date");
				}
				if(isset($inputs['name'])){
					$comment->name = $inputs['name'];
				}
				if(isset($inputs['email'])){
					$comment->email = $inputs['email'];
				}
				if(isset($inputs['text'])){
					$comment->text = $inputs['text'];
				}
				if(isset($inputs['date'])){
					$comment->date = $inputs['date'];
				}
				if(isset($inputs['status'])){
					$comment->status = $inputs['status'];
				}
				$comment->save();
				$this->response->setStatus(true);
				$this->response->Go(userpanel\url('news/comment/edit/'.$comment->id));

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
