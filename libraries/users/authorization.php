<?php
namespace packages\news;
use \packages\userpanel\authorization as UserPanelAuthorization;

class authorization extends UserPanelAuthorization{
	static function is_accessed($permission, $prefix = 'news'){
		return parent::is_accessed($permission, $prefix);
	}
	static function haveOrFail($permission, $prefix = 'news'){
		parent::haveOrFail($permission, $prefix);
	}
}
