<?php
namespace packages\news\listeners\settings;
use \packages\userpanel\usertype\permissions;
class usertype{
	public function permissions_list(){
		$permissions = array(
			'list',
			'add',
			'edit',
			'delete',
			'comments_list',
			'comments_edit',
			'comments_delete'
		);
		foreach($permissions as $permission){
			permissions::add('news_'.$permission);
		}
	}
}
