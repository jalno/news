<?php
namespace packages\news;
use \packages\base\DB;
use \packages\base\DB\DBObject;
use \packages\base\HTTP;
class NewPost extends DBObject{
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
		'author' => array('hasOne', \packages\userpanel\User::class, 'author'),
		'comments' => array('hasMany', \packages\news\Comment::class, 'post'),
		'files' => array('hasMany', \packages\news\File::class, 'post')
	);
	public function breakContent(){
		$content = array($this->content,'');
		if(preg_match("/([\s\S]*)<div+.+style=\"page-break-after:always\".*>.*<\/div>([\s\S]*)/im", $this->content, $matches)){
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
	public function getCountPostCommnets(){
		DB::where("post", $this->id);
		DB::where("status", Comment::accepted);
		return DB::getValue("news_comments", "count(*)");
	}
	public function setAttachments(array $attachments){
		$files = $this->files;
		foreach($attachments as $attachment){
			$found = false;
			foreach($files as $key => $file){
				if($file->id == $attachment->id){
					$found = true;
					unset($files[$key]);
					break;
				}
			}
			if(!$found){
				$attachment->post = $this->id;
				$attachment->save();
			}
		}
		foreach($files as $file){
			$file->post = null;
			$file->save();
		}
	}
}
