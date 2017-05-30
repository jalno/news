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
			$new->pageLimit = $this->items_per_page;
			$news = $new->paginate($this->page);
			$view->setDataList($news);
			$view->setPaginate($this->page, base\db::totalCount(), $this->items_per_page);
			$view->setNews($news);
			$this->response->setView($view);
			return $this->response;
		}
	}
	public function view($data){
		if(!$new = newpost::byId($data['id'])){
			throw new NotFound();
		}
		$view = view::byName("\\packages\\news\\views\\news\\view");
		$new->view += 1;
		$new->save();
		$view->setNew($new);
		$view->setData($new->comments, 'comments');
		if(http::is_post()){
			$this->response->setStatus(false);
			$inputsRules = [
				'reply' => [
					'type' => 'number',
					'optional' => true,
					'empty' => true
				],
				'name' => [
					'type' => 'string'
				],
				'email' => [
					'type' => 'email',
				],
				'text' => [
					'type' => 'string'
				]
			];
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
	public function archive($data){
		$first = date::mktime(0, 0, 0, $data['month'], 1, $data['year']);
		$last = date::mktime(0, 0, 0, $data['month'], 30, $data['year']);
		$new = new newpost();
		$new->orderBy('date', 'DESC');
		$new->where('date', $first, '>');
		$new->where('date', $last, '<');
		$new->where('status', newpost::published);
		$new->pageLimit = $this->items_per_page;
		if(!$news = $new->paginate($this->page)){
			throw new NotFound();
		}
		$view = view::byName("\\packages\\news\\views\\news\\archive");
		$view->setNews($news);
		$view->setPaginate($this->page, base\db::totalCount(), $this->items_per_page);
		$this->response->setStatus(true);
		$this->response->setView($view);
		return $this->response;
	}
	public function author($data){
		$new = new newpost();
		$new->where('author', $data['id']);
		$new->orderBy('date', 'DESC');
		$new->where('status', newpost::published);
		$new->pageLimit = $this->items_per_page;
		if(!$news = $new->paginate($this->page)){
			throw new NotFound();
		}
		$view = view::byName("\\packages\\news\\views\\news\\author");
		$view->setNews($news);
		$view->setPaginate($this->page, base\db::totalCount(), $this->items_per_page);
		$this->response->setStatus(true);
		$this->response->setView($view);
		return $this->response;
	}
}
