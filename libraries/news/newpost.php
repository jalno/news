<?php
namespace packages\news;
use \packages\base\db;
use \packages\base\db\dbObject;
use \packages\base\http;

class newpost extends dbObject{
	const published = 1;
	const unpublished = 2;
	protected $dbTable = "news_posts";
	protected $primaryKey = "id";
	protected $dbFields = array(
		'title' => array('type' => 'text', 'required' => true),
		'date' => array('type' => 'int', 'required' => true),
        'description' => array('type' => 'text', 'required' => true),
        'author' => array('type' => 'int', 'required' => true),
        'content' => array('type' => 'text', 'required' => true),
		'view' => array('type' => 'int'),
		'image' => array('type' => 'text'),
        'status' => array('type' => 'int', 'required' => true),
    );
    protected $relations = array(
		'author' => array('hasOne', 'packages\\userpanel\\user', 'author'),
		'comments' => array('hasMany', '\\packages\\news\\comment', 'post')
	);
	public function breakContent(){
		$content = array($this->content,'');
		if(preg_match("/([\\s\\S]+)<!-+more-+>([\\s\\S]+)/im", $this->content, $matches)){
			$content = array($matches[1], $matches[2]);
		}
		return $content;
	}
	public function firstPartContent(){
		return $this->breakContent()[0];
	}
	public function secondPartContent(){
		return $this->breakContent()[1];
	}

}
