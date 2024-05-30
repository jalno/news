<?php
namespace packages\news;
use \packages\base\Packages;
use \packages\base\DB\DBObject;
class File extends DBObject{
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
		'post' => array('hasOne', \packages\news\NewPost::class, 'post')
	);
	public function url($absolute = false){
		return Packages::package('news')->url($this->file, $absolute);
	}
}
