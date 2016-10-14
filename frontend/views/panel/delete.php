<?php
namespace themes\clipone\views\news\panel;
use \packages\news\views\panel\delete as newDelete;
use \packages\base\frontend\theme;
use \packages\userpanel;
use \themes\clipone\navigation;
use \themes\clipone\navigation\menuItem;
use \themes\clipone\views\formTrait;
use \themes\clipone\viewTrait;
use \packages\base\translator;

class delete extends newDelete{
	use viewTrait,formTrait;
	function __beforeLoad(){
		$this->setTitle(array(
			translator::trans('news'),
			translator::trans('delete')
		));
		navigation::active("news");
	}
}
