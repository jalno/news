<?php
namespace packages\news\Controllers\Panel;
use \packages\base\DB;
use \packages\base\HTTP;
use \packages\base\NotFound;
use \packages\base\DB\Parenthesis;
use \packages\base\InputValidation;
use \packages\base\Views\FormError;
use \packages\base\Packages;
use \packages\userpanel\Controller;
use \packages\userpanel;
use \packages\userpanel\User;
use \packages\userpanel\Date;
use \packages\userpanel\View;
use \packages\news\Events;
use \packages\news\NewPost as Post;
use \packages\news\Comment;
use \packages\news\File;
use \packages\news\Authorization;
use \packages\news\Views\Panel;
class News extends Controller{
	protected $authentication = true;
	private function getNew($id){
		if(!$new = Post::byId($id)){
			throw new NotFound;
		}
		return $new;
	}
	public function index(){
		Authorization::haveOrFail('list');
		$view = View::byName(Panel\Index::class);
		$post = new Post();
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
				if(!User::byId($inputs['author'])){
					throw new InputValidation('authro');
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
				$parenthesis = new Parenthesis();
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
			$view->setPaginate($this->page, DB::totalCount(), $this->items_per_page);
		}catch(InputValidation $error){
			$view->setFormError(FormError::fromException($error));
			$this->response->setStatus(false);
		}
		$this->response->setView($view);
		return $this->response;
	}
	public function edit($data){
		Authorization::haveOrFail('edit');
		$view = View::byName(Panel\Edit::class);
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
				'values' => [Post::published, Post::unpublished]
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
		if(HTTP::is_post()){
			try{
				$inputs = $this->checkinputs($inputsRules);
				if(isset($inputs['author'])){
					$inputs['author'] = User::byId($inputs['author']);
					if(!$inputs['author']){
						throw new InputValidation("author");
					}
				}
				if(isset($inputs['date'])){
					$inputs['date'] = Date::strtotime($inputs['date']);
					if($inputs['date'] <= 0){
						throw new InputValidation("date");
					}
				}
				if(isset($inputs['attachment'])){
					foreach($inputs['attachment'] as $key => $attachment){
						if($file = File::byID($attachment)){
							$inputs['attachment'][$key] = $file;
						}else{
							throw new InputValidation("attachment[{$key}]");
						}
					}
				}

				if(isset($inputs['image'])){
					if($inputs['image']['error'] == 0){
						$type = getimagesize($inputs['image']['tmp_name']);
						if(!in_array($type[2], [IMAGETYPE_JPEG ,IMAGETYPE_GIF, IMAGETYPE_PNG])){
							throw new InputValidation("image");
						}
					}elseif($inputs['image']['error'] == 4){
						unset($inputs['image']);
					}else{
						throw new InputValidation("image");
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
						throw new InputValidation("image");
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
			}catch(InputValidation $error){
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
		Authorization::haveOrFail('delete');
		$view = View::byName(Panel\Delete::class);
		$new = $this->getNew($data['id']);
		$view->setNew($new);
		$this->response->setStatus(false);
		if(HTTP::is_post()){
			try {
				$new->delete();
				$this->response->setStatus(true);
				$this->response->Go(userpanel\url('news'));
			}catch(InputValidation $error){
				$view->setFormError(FormError::fromException($error));
			}
		}else{
			$this->response->setStatus(true);
		}
		$this->response->setView($view);
		return $this->response;
	}
	public function add(){
		Authorization::haveOrFail('add');
		$view = View::byName(Panel\Add::class);
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
				'values' => [Post::published, Post::unpublished]
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
		if(HTTP::is_post()){
			try{
				$inputs = $this->checkinputs($inputsRules);
				$inputs['author'] = User::byId($inputs['author']);
				$inputs['date'] = Date::strtotime($inputs['date']);
				if(!$inputs['author']){
					throw new InputValidation("author");
				}
				if($inputs['date'] <= 0){
					throw new InputValidation("date");
				}
				if(isset($inputs['attachment'])){
					foreach($inputs['attachment'] as $key => $attachment){
						if($file = File::byID($attachment)){
							$inputs['attachment'][$key] = $file;
						}else{
							throw new InputValidation("attachment[{$key}]");
						}
					}
				}
				if(isset($inputs['image'])){
					if($inputs['image']['error'] == 0){
						$type = getimagesize($inputs['image']['tmp_name']);
						if(!in_array($type[2], [IMAGETYPE_JPEG ,IMAGETYPE_GIF, IMAGETYPE_PNG])){
							throw new InputValidation("image");
						}
					}elseif($inputs['image']['error'] == 4){
						unset($inputs['image']);
					}else{
						throw new InputValidation("image");
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
						throw new InputValidation("image");
					}
					$inputs['image'] = "storage/".$name.$type_name;
				}
				$post = new Post;
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
				$event = new Events\Posts\Add($post);
				$event->trigger();
				$this->response->setStatus(true);
				$this->response->Go(userpanel\url('news/edit/'.$post->id));
			}catch(InputValidation $error){
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
		$view = View::byName(Panel\View::class);
		if(!$new = Post::byId($data['id'])){
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
