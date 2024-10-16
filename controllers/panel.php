<?php
namespace packages\news\controllers\panel;
use \packages\base\db;
use \packages\base\http;
use \packages\base\NotFound;
use \packages\base\db\parenthesis;
use \packages\base\inputValidation;
use \packages\base\views\FormError;
use \packages\base\packages;
use \packages\userpanel\controller;
use \packages\userpanel;
use \packages\userpanel\user;
use \packages\userpanel\date;
use \packages\userpanel\view;
use \packages\news\events;
use \packages\news\newpost as post;
use \packages\news\comment;
use \packages\news\file;
use \packages\news\authorization;
class news extends controller{
	protected $authentication = true;
	private function getNew($id){
		if(!$new = post::byId($id)){
			throw new NotFound;
		}
		return $new;
	}
	public function index(){
		authorization::haveOrFail('list');
		$view = view::byName("\\packages\\news\\views\\panel\\index");
		$post = new post();
		$inputsRules = [
			'id' => [
				'type' => 'number',
				'empty' => true,
				'optional' => true
			],
			'author' => [
				'type' => 'number',
				'empty' => true,
				'optional' => true
			],
			'title' => [
				'type' => 'string',
				'empty' => true,
				'optional' => true
			],
			'word' => [
				'type' => 'string',
				'optional' => true,
				'empty' => true
			],
			'comparison' => [
				'values' => ['equals', 'startswith', 'contains'],
				'default' => 'contains',
				'optional' => true
			]
		];
		try{
			$inputs = $this->checkinputs($inputsRules);
			if(isset($inputs['author']) and $inputs['author']){
				if(!user::byId($inputs['author'])){
					throw new inputValidation('authro');
				}
			}
			foreach(['id', 'author', 'title', 'status'] as $item){
				if(isset($inputs[$item]) and $inputs[$item]){
					$comparison = $inputs['comparison'];
					if(in_array($item, ['id', 'status'])){
						$comparison = 'equals';
					}
					$post->where("news_posts.".$item, $inputs[$item], $comparison);
				}
			}
			if(isset($inputs['word']) and $inputs['word']){
				$parenthesis = new parenthesis();
				foreach(['title', 'description', 'content'] as $item){
					if(!isset($inputs[$item]) or !$inputs[$item]){
						$parenthesis->where("news_posts.".$item, $inputs['word'], $inputs['comparison'], 'OR');
					}
				}
				$post->where($parenthesis);
			}
			$post->pageLimit = $this->items_per_page;
			$posts = $post->paginate($this->page);
			$view->setPaginate($this->page, $post->totalCount, $this->items_per_page);
			$view->setDataList($posts);
			$view->setPaginate($this->page, db::totalCount(), $this->items_per_page);
		}catch(inputValidation $error){
			$view->setFormError(FormError::fromException($error));
			$this->response->setStatus(false);
		}
		$this->response->setView($view);
		return $this->response;
	}
	public function edit($data){
		authorization::haveOrFail('edit');
		$view = view::byName("\\packages\\news\\views\\panel\\edit");
		$post = $this->getNew($data['id']);
		$view->setNew($post);
		$inputsRules = [
			'title' => [
				'type' => 'string',
				'optional' => true
			],
			'author' => [
				'type' => 'number',
				'optional' => true
			],
			'description' => [
				'type' => 'string',
				'optional' => true
			],
			'date' => [
				'type' => 'date',
				'optional' => true
			],
			'status' => [
				'type' => 'number',
				'optional' => true,
				'values' => [post::published, post::unpublished]
			],
			'image' => [
				'type' => 'file',
				'optional' => true,
				'empty' => true
			],
			'text' => [
				'optional' => true
			],
			'content' => [
				'optional' => true
			],
			'attachment' => [
				'optional' => true
			]
		];
		$this->response->setStatus(false);
		if(http::is_post()){
			try{
				$inputs = $this->checkinputs($inputsRules);
				if(isset($inputs['author'])){
					$inputs['author'] = user::byId($inputs['author']);
					if(!$inputs['author']){
						throw new inputValidation("author");
					}
				}
				if(isset($inputs['date'])){
					$inputs['date'] = date::strtotime($inputs['date']);
					if($inputs['date'] <= 0){
						throw new inputValidation("date");
					}
				}
				if(isset($inputs['attachment'])){
					foreach($inputs['attachment'] as $key => $attachment){
						if($file = file::byID($attachment)){
							$inputs['attachment'][$key] = $file;
						}else{
							throw new inputValidation("attachment[{$key}]");
						}
					}
				}

				if(isset($inputs['image'])){
					if($inputs['image']['error'] == 0){
						$type = getimagesize($inputs['image']['tmp_name']);
						if(!in_array($type[2], [IMAGETYPE_JPEG ,IMAGETYPE_GIF, IMAGETYPE_PNG])){
							throw new inputValidation("image");
						}
					}elseif($inputs['image']['error'] == 4){
						unset($inputs['image']);
					}else{
						throw new inputValidation("image");
					}
				}
				if(isset($inputs['image'])){
					$name = md5_file($inputs['image']['tmp_name']);
					if($type[2] == IMAGETYPE_JPEG){
						$type_name = '.jpg';
					}elseif($type[2] == IMAGETYPE_GIF){
						$type_name = '.gif';
					}elseif($type[2] == IMAGETYPE_PNG){
						$type_name = '.png';
					}
					$directory = __DIR__.'/../storage/'.$name.$type_name;
					if(!move_uploaded_file($inputs['image']['tmp_name'], $directory)){
						throw new inputValidation("image");
					}
					$inputs['image'] = "storage/".$name.$type_name;
				}
				foreach(['author', 'date', 'image', 'status', 'content', 'title', 'description'] as $key){
					if(isset($inputs[$key])){
						$post->$key = $inputs[$key];
					}
				}
				$post->save();
				if(isset($inputs['attachment'])){
					$post->setAttachments($inputs['attachment']);
				}
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
	public function delete($data){
		authorization::haveOrFail('delete');
		$view = view::byName("\\packages\\news\\views\\panel\\delete");
		$new = $this->getNew($data['id']);
		$view->setNew($new);
		$this->response->setStatus(false);
		if(http::is_post()){
			try {
				$new->delete();
				$this->response->setStatus(true);
				$this->response->Go(userpanel\url('news'));
			}catch(inputValidation $error){
				$view->setFormError(FormError::fromException($error));
			}
		}else{
			$this->response->setStatus(true);
		}
		$this->response->setView($view);
		return $this->response;
	}
	public function add(){
		authorization::haveOrFail('add');
		$view = view::byName("\\packages\\news\\views\\panel\\add");
		$inputsRules = [
			'title' => [
				'type' => 'string'
			],
			'author' => [
				'type' => 'number'
			],
			'description' => [
				'type' => 'string'
			],
			'date' => [
				'type' => 'date'
			],
			'status' => [
				'type' => 'number',
				'values' => [post::published, post::unpublished]
			],
			'image' => [
				'type' => 'file',
				'optional' => true,
				'empty' => true
			],
			'content' => [],
			'attachment' => [
				'optional' => true
			]
		];
		$this->response->setStatus(false);
		if(http::is_post()){
			try{
				$inputs = $this->checkinputs($inputsRules);
				$inputs['author'] = user::byId($inputs['author']);
				$inputs['date'] = date::strtotime($inputs['date']);
				if(!$inputs['author']){
					throw new inputValidation("author");
				}
				if($inputs['date'] <= 0){
					throw new inputValidation("date");
				}
				if(isset($inputs['attachment'])){
					foreach($inputs['attachment'] as $key => $attachment){
						if($file = file::byID($attachment)){
							$inputs['attachment'][$key] = $file;
						}else{
							throw new inputValidation("attachment[{$key}]");
						}
					}
				}
				if(isset($inputs['image'])){
					if($inputs['image']['error'] == 0){
						$type = getimagesize($inputs['image']['tmp_name']);
						if(!in_array($type[2], [IMAGETYPE_JPEG ,IMAGETYPE_GIF, IMAGETYPE_PNG])){
							throw new inputValidation("image");
						}
					}elseif($inputs['image']['error'] == 4){
						unset($inputs['image']);
					}else{
						throw new inputValidation("image");
					}
				}
				if(isset($inputs['image'])){
					$name = md5_file($inputs['image']['tmp_name']);
					if($type[2] == IMAGETYPE_JPEG){
						$type_name = '.jpg';
					}elseif($type[2] == IMAGETYPE_GIF){
						$type_name = '.gif';
					}elseif($type[2] == IMAGETYPE_PNG){
						$type_name = '.png';
					}
					$directory = __DIR__.'/../storage/'.$name.$type_name;
					if(!move_uploaded_file($inputs['image']['tmp_name'], $directory)){
						throw new inputValidation("image");
					}
					$inputs['image'] = "storage/".$name.$type_name;
				}
				$post = new post;
				foreach(['author', 'date', 'image', 'content', 'status', 'title', 'description'] as $item){
					if(isset($inputs[$item])){
						$post->$item = $inputs[$item];
					}
				}
				$post->save();
				if(isset($inputs['attachment'])){
					foreach($inputs['attachment'] as $file){
						$file->post = $post->id;
						$file->save();
					}
				}
				$event = new events\posts\add($post);
				$event->trigger();
				$this->response->setStatus(true);
				$this->response->Go(userpanel\url('news/edit/'.$post->id));
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
	public function view($data){
		$view = view::byName("\\packages\\news\\views\\panel\\view");
		if(!$new = post::byId($data['id'])){
			throw new NotFound();
		}
		$new->view += 1;
		$new->save();
		$view->setNew($new);
		$this->response->setView($view);
		$this->response->setStatus(true);
		return $this->response;
	}
}
