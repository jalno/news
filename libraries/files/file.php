<?php
namespace packages\news;
use \packages\base\packages;
use \packages\base\db\dbObject;
class file extends dbObject{
	protected $dbTable = "news_files";
	protected $primaryKey = "id";
	protected $dbFields = array(
		'post' => array('type' => 'int'),
		'file' => array('type' => 'text', 'required' => true),
        'name' => array('type' => 'text', 'required' => true),
        'size' => array('type' => 'int'),
        'md5' => array('type' => 'text', 'required' => true)
    );
    protected $relations = array(
		'post' => array('hasOne', 'packages\\news\\newpost', 'post')
	);
	public function url($absolute = false){
		return packages::package('news')->url($this->file, $absolute);
	}
}
