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


class news extends controller{
	protected $authentication = true;
	private function getNew($id){
		if(!$new = newpost::byId($id)){
			throw new NotFound;
		}
		return $new;
	}
	public function index(){
		authorization::haveOrFail('list');
		$view = view::byName("\\packages\\news\\views\\panel\\index");
		$new = new newpost();
		$new->pageLimit = $this->items_per_page;
		$news = $new->paginate($this->page);
		$view->setNews($news);
		$this->total_pages = $new->totalPages;
		$view->setPaginate($this->page, $new->totalCount, $this->items_per_page);
		$this->response->setView($view);
		return $this->response;
	}
	public function view($data){
		if($view = view::byName("\\packages\\news\\views\\panel\\view")){
			$new = $this->getNew($data['id']);
			$new->view += 1;
			$new->save();
			$view->setNew($new);
			$this->response->setView($view);
			return $this->response;
		}
	}
	public function edit($data){
		authorization::haveOrFail('edit');
		$view = view::byName("\\packages\\news\\views\\panel\\edit");
		$news = $this->getNew($data['id']);
		$view->setNew($news);
		$inputsRules = array(
			'title' => array(
				'type' => 'string',
				'optional' => true
			),
			'author' => array(
				'type' => 'number',
				'optional' => true
			),
			'description' => array(
				'type' => 'string',
				'optional' => true
			),
			'date' => array(
				'type' => 'date',
				'optional' => true
			),
			'status' => array(
				'type' => 'number',
				'optional' => true,
				'values' => array(newpost::published, newpost::unpublished)
			),
			'image' => array(
				'type' => 'file',
				'optional' => true,
				'empty' => true
			),
			'text' => array(
				'optional' => true
			)
		);
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
				$news->author = $inputs['author'];
				$news->date = $inputs['date'];
				if(isset($inputs['image'])){
					if($inputs['image']['error'] == 0){
						$type = getimagesize($inputs['image']['tmp_name']);
						if(in_array($type[2], array(IMAGETYPE_JPEG ,IMAGETYPE_GIF, IMAGETYPE_PNG))){
							$name = md5_file($inputs['image']['tmp_name']);
							if($type[2] == IMAGETYPE_JPEG){
								$type_name = '.jpg';
							}elseif($type[2] == IMAGETYPE_GIF){
								$type_name = '.gif';
							}elseif($type[2] == IMAGETYPE_PNG){
								$type_name = '.png';
							}
							$directory = __DIR__.'/../storage/'.$name.$type_name;
							if(move_uploaded_file($inputs['image']['tmp_name'], $directory)){
								$news->image = "storage/".$name.$type_name;
							}else{
								throw new inputValidation("image");
							}
						}else{
							throw new inputValidation("image");
						}
					}elseif($inputs['image']['error'] != 4){
						throw new inputValidation("image");
					}
				}
				if(isset($inputs['status'])){
					$news->status = $inputs['status'];
				}
				if(isset($inputs['text'])){
					$news->content = $inputs['text'];
				}
				if(isset($inputs['title'])){
					$news->title = $inputs['title'];
				}
				if(isset($inputs['description'])){
					$news->description = $inputs['description'];
				}
				$news->save();
				$this->response->setStatus(true);
				$this->response->Go(userpanel\url('news/edit/'.$news->id));
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
		$inputsRules = array(
			'title' => array(
				'type' => 'string'
			),
			'author' => array(
				'type' => 'number'
			),
			'description' => array(
				'type' => 'string'
			),
			'date' => array(
				'type' => 'date'
			),
			'status' => array(
				'type' => 'number',
				'values' => array(newpost::published, newpost::unpublished)
			),
			'image' => array(
				'type' => 'file',
				'optional' => true,
				'empty' => true
			),
			'text' => array()
		);

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
				$news = new newpost;
				$news->author = $inputs['author'];
				$news->date = $inputs['date'];
				if(isset($inputs['image'])){
					if($inputs['image']['error'] == 0){
						$type = getimagesize($inputs['image']['tmp_name']);
						if(in_array($type[2], array(IMAGETYPE_JPEG ,IMAGETYPE_GIF, IMAGETYPE_PNG))){
							$name = md5_file($inputs['image']['tmp_name']);
							if($type[2] == IMAGETYPE_JPEG){
								$type_name = '.jpg';
							}elseif($type[2] == IMAGETYPE_GIF){
								$type_name = '.gif';
							}elseif($type[2] == IMAGETYPE_PNG){
								$type_name = '.png';
							}
							$directory = __DIR__.'/../storage/'.$name.$type_name;
							if(move_uploaded_file($inputs['image']['tmp_name'], $directory)){
								$news->image = "storage/".$name.$type_name;
							}else{
								throw new inputValidation("image");
							}
						}else{
							throw new inputValidation("image");
						}
					}elseif($inputs['image']['error'] != 4){
						throw new inputValidation("image");
					}
				}
				$news->status = $inputs['status'];
				$news->content = $inputs['text'];
				$news->title = $inputs['title'];
				$news->description = $inputs['description'];
				$news->save();
				$this->response->setStatus(true);
				$this->response->Go(userpanel\url('news/edit/'.$news->id));
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
