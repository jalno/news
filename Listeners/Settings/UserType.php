<?php
namespace packages\news\Listeners\Settings;
use \packages\userpanel\UserType\Permissions;
class UserType{
	public function permissions_list(){
		$permissions = array(
			'list',
			'add',
			'edit',
			'delete',
			'comments_list',
			'comments_edit',
			'comments_delete',
			'files_upload',
			'files_delete'
		);
		foreach($permissions as $permission){
			Permissions::add('news_'.$permission);
		}
	}
}
