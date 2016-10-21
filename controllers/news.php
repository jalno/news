<?php
namespace packages\news\controllers;
use \packages\base;
use \packages\base\http;
use \packages\base\NotFound;
use \packages\base\inputValidation;
use \packages\base\views\FormError;

use \packages\news\controller;
use \packages\news\view;
use \packages\news\newpost;
use \packages\news\comment;

use \packages\userpanel\date;

class news extends controller{
	public function index(){
		if($view = view::byName("\\packages\\news\\views\\news\\index")){
			$new = new newpost();
			$new->orderBy('date', 'DESC');
			$new->where('status', newpost::published);
			$news = $new->get();

			$view->setNews($news);
			$this->response->setView($view);
			return $this->response;
		}
	}
	public function view($data){
		if($view = view::byName("\\packages\\news\\views\\news\\view")){

			if($new = newpost::byId($data['id'])){
				$new->view += 1;
				$new->save();
				$view->setNew($new);
				$view->setData($new->comments, 'comments');
			}else{
				throw new NotFound();
			}

			$this->response->setStatus(false);
			if(http::is_post()){
				$inputsRules = array(
					'reply' => array(
						'type' => 'number',
						'optional' => true,
						'empty' => true
					),
					'name' => array(
						'type' => 'string'
					),
					'email' => array(
						'type' => 'email',
					),
					'text' => array(
						'type' => 'string'
					)
				);
				try{
					$inputs = $this->checkinputs($inputsRules);
					$comment = new comment($inputs);
					$comment->post = $data['id'];
					if(isset($inputs['reply'])){
						$comment->reply = $inputs['reply'];
					}
					$comment->save();
					$this->response->setStatus(true);
				}catch(inputValidation $error){
					$view->setFormError(FormError::fromException($error));
				}
				$view->setDataForm($this->inputsvalue($inputsRules));

			}else{
				$this->response->setStatus(true);


			}
			$this->response->setView($view);
			return $this->response;
		}
	}
	public function archive($data){
		if($view = view::byName("\\packages\\news\\views\\news\\archive")){

			$first = date::mktime(0, 0, 0, $data['month'], 1,$data['year']);
			$last = date::mktime(0, 0, 0, $data['month'], 30,$data['year']);

			if($newpost = newpost::where('date', $first, '>')->where('date', $last, '<')){
				$newpost->orderBy('date', 'DESC');
				$view->setNews($newpost->get());
			}else{
				throw new NotFound();
			}

			$this->response->setView($view);
			return $this->response;
		}
	}
	public function author($data){
		if($view = view::byName("\\packages\\news\\views\\news\\author")){
			if($newpost = newpost::where('author', $data['id'])){
				$newpost->orderBy('date', 'DESC');
				$view->setNews($newpost->get());
			}

			$this->response->setView($view);
			return $this->response;
		}
	}
}
