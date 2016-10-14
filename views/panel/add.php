<?php
namespace packages\news\views\panel;
use \packages\news\views\form;
use \packages\news\authorization;
class add extends form{
	static protected $navigation;

	public static function onSourceLoad(){
		self::$navigation = authorization::is_accessed('list');
	}
}
