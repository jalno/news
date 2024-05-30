<?php
namespace themes\clipone\Views\News\Panel;
use \packages\news\Views\Panel\Delete as NewDelete;
use \packages\base\Frontend\Theme;
use \packages\userpanel;
use \themes\clipone\Navigation;
use \themes\clipone\Navigation\MenuItem;
use \themes\clipone\Views\FormTrait;
use \themes\clipone\ViewTrait;
use \packages\base\Translator;

class Delete extends NewDelete{
	use ViewTrait,FormTrait;
	function __beforeLoad(){
		$this->setTitle(array(
			Translator::trans('news'),
			Translator::trans('delete')
		));
		Navigation::active("news/index");
	}
}
