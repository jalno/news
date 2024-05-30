<?php
namespace themes\clipone\Views\News\Panel;
use \packages\base\Frontend\Theme;
use \packages\userpanel;
use \themes\clipone\Navigation;
use \themes\clipone\Navigation\MenuItem;
use \themes\clipone\Views\FormTrait;
use \themes\clipone\ViewTrait;
use \packages\base\Translator;

class CommentDelete extends \packages\news\Views\Panel\CommentDelete{
	use ViewTrait,FormTrait;
	function __beforeLoad(){
		$this->setTitle(array(
			Translator::trans('comment'),
			Translator::trans('delete')
		));
		Navigation::active("news/comments");
	}
}
