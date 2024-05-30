<?php
namespace packages\news\Views\Panel;
use \packages\news\Views\Form;
use \packages\news\Authorization;
class Add extends Form{
	static protected $navigation;

	public static function onSourceLoad(){
		self::$navigation = Authorization::is_accessed('list');
	}
}
