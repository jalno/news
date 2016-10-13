<?php
namespace packages\news;
use \packages\base\db;
use \packages\base\db\dbObject;
use \packages\base\http;

class comment extends dbObject{
	const accepted = 1;
	const pending = 2;
	const unverified = 3;
	protected $dbTable = "news_comments";
	protected $primaryKey = "id";
	protected $dbFields = array(
		'post' => array('type' => 'int', 'required' => true),
		'reply' => array('type' => 'int'),
		'email' => array('type' => 'text', 'required' => true),
		'name' => array('type' => 'text', 'required' => true),
        'date' => array('type' => 'int', 'required' => true),
		'text' => array('type' => 'text', 'required' => true),
        'status' => array('type' => 'int', 'required' => true)
    );
	protected $relations = array(
		'new' => array('hasOne', 'packages\\news\\newpost', 'post')
	);
	protected function preLoad($data){
		if(!isset($data['date'])){
			$data['date'] = time();
		}
		if(!isset($data['status'])){
			$data['status'] = self::pending;
		}
		return $data;

	}
}
