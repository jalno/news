<?php
namespace themes\clipone\views\news\panel;
use \packages\base\frontend\theme;
use \packages\userpanel;
use \themes\clipone\navigation;
use \themes\clipone\navigation\menuItem;
use \themes\clipone\views\formTrait;
use \themes\clipone\viewTrait;
use \packages\base\translator;

class commentDelete extends \packages\news\views\panel\commentDelete{
	use viewTrait,formTrait;
	function __beforeLoad(){
		$this->setTitle(array(
			translator::trans('comment'),
			translator::trans('delete')
		));
		navigation::active("news/comments");
	}
}
