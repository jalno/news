<?php
namespace packages\news\controllers;


use \packages\base;
use \packages\userpanel;
use packages\userpanel\date;
use packages\base\{View\Error, views\FormError, HTTP, InputValidation, NoViewException, NotFound, Response};
use packages\news\{Comment, Controller, events, Newpost, View};

class News extends Controller {

	public function index(): Response {
		$view = View::byName(\packages\news\views\news\Index::class);
		if ($view) {
			$this->response->setView($view);
			$new = new newpost();
			$new->orderBy('date', 'DESC');
			$new->where('status', newpost::published);
			$new->pageLimit = $this->items_per_page;
			$news = $new->paginate($this->page);
			$view->setDataList($news);
			$view->setPaginate($this->page, base\db::totalCount(), $this->items_per_page);
			$view->setNews($news);
			$this->response->setStatus(true);
		}
		return $this->response;
	}
	public function view($data){
		if(!$new = newpost::byId($data['id'])){
			throw new NotFound();
		}
		try{
			$view = view::byName("\\packages\\news\\views\\news\\view");
		}catch(NoViewException $error){
			$this->response->Go(userpanel\url('news/view/' . $new->id));
		}
		$new->view++;
		$new->save();
		$view->setNew($new);
		$comment = new Comment();
		$comment->where("post", $new->id);
		$comment->where("status", Comment::accepted);
		$view->setData($comment->get(), 'comments');
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
				if(isset($inputs['reply'])){
					if($inputs['reply']){
						if(!$inputs['reply'] = comment::byId($inputs['reply'])){
							throw new inputValidation('reply');
						}
					}else{
						unset($inputs['reply']);
					}
				}
				$comment = new comment();
				$comment->post = $new->id;
				foreach(['email', 'name', 'text'] as $item){
					$comment->$item = $inputs[$item];
				}
				if(isset($inputs['reply'])){
					$comment->reply = $inputs['reply']->id;
				}
				$comment->save();
				$event = new events\comments\add($comment);
				$event->trigger();
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
		$month = $data["month"];
		$year = $data["year"];
		if ($month == 12) {
			$month = 1;
			$year++;
		} else {
			$month++;
		}
		$last = date::mktime(0, 0, 0, $month, 1, $year);
		$new = new newpost();
		$new->orderBy('date', 'DESC');
		$new->where('date', $first, '>=');
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
