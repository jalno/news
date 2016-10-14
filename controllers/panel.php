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
	public function index(){
		authorization::haveOrFail('list');
		$view = view::byName("\\packages\\news\\views\\panel\\index");
		$new = new newpost();
		$new->orderBy('date', 'DESC');
		$view->setNews($new->get());
		$this->response->setView($view);
		return $this->response;
	}
	public function comments($data){
		authorization::haveOrFail('list');
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
	public function edit($data){
		authorization::haveOrFail('edit');
		$view = view::byName("\\packages\\news\\views\\panel\\edit");
		$new = new newpost;
		$new->where('id', $data['id']);
		$news = $new->getOne();
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
				if(isset($inputs['image']) and $inputs['image']['tmp_name'] != ''){
					$type = getimagesize($inputs['image']['tmp_name']);
					if(in_array($type[2], array(IMAGETYPE_JPEG ,IMAGETYPE_GIF, IMAGETYPE_PNG))){
						if($inputs['image']['error'] == 0){
							$name = md5_file($inputs['image']['tmp_name']);
							if($type[2] == IMAGETYPE_JPEG){
								$type_name = '.jpeg';
							}elseif($type[2] == IMAGETYPE_GIF){
								$type_name = '.gif';
							}elseif($type[2] == IMAGETYPE_PNG){
								$type_name = '.png';
							}
							$directory = __DIR__.'/../storage/'.$name.$type_name;
							if(move_uploaded_file($inputs['image']['tmp_name'], $directory)){

								$newspackage = packages::package('news');

								$news->image = $newspackage->url("storage/".$name.$type_name);
							}else{
								throw new inputValidation("image");
							}
						}else{
							throw new \Exception("image");
						}
					}else{
						throw new inputValidation("image");
					}
				}
				$news->status = $inputs['status'];
				$news->content = $inputs['text'];
				$news->title = $inputs['title'];
				$news->description = $inputs['description'];
				$news->save();
				$this->response->setStatus(true);
				$this->response->Go(userpanel\url('news'));
			}catch(inputValidation $error){
				var_dump($error);
				exit();
				$view->setFormError(FormError::fromException($error));
			}
		}else{
			$this->response->setStatus(true);
		}
		$this->response->setView($view);
		return $this->response;
	}
}
