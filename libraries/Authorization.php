<?php
namespace packages\news;
use \packages\userpanel\Authorization as UserPanelAuthorization;

class Authorization extends UserPanelAuthorization{
	static function is_accessed($permission, $prefix = 'news'){
		return parent::is_accessed($permission, $prefix);
	}
	static function haveOrFail($permission, $prefix = 'news'){
		parent::haveOrFail($permission, $prefix);
	}
}
