<?php
namespace themes\clipone\views\news\panel;
use \packages\news\views\panel\add as newADD;
use \packages\base\translator;
use \packages\base\options;
use \packages\base\packages;
use \packages\userpanel\user;
use \themes\clipone\navigation;
use \themes\clipone\views\formTrait;
use \themes\clipone\viewTrait;
use \packages\news\authentication;
use \packages\base\frontend\theme;
class add extends newADD{
	use viewTrait, formTrait;
	function __beforeLoad(){
		$this->setTitle(array(
			translator::trans('news'),
			translator::trans('add')
		));
		navigation::active("news/addnew");
		$this->setUserInput();
		$this->addJSFile(theme::url("assets/plugins/ckeditor/ckeditor.js"));
	}
	function getImage(){
		$newspackage = packages::package('news');
		return ($newspackage->url(options::get('packages.news.defaultimage')));
	}
	private function setUserInput(){
		if($error = $this->getFormErrorsByInput('author')){
			$error->setInput('author_name');
			$this->setFormError($error);
		}
		$user = $this->getDataForm('author');
		if($user){
			$user = user::byId($user);
			$this->setDataForm($user->getFullName(), 'author_name');
		}else{
			$me = authentication::getUser();
			$this->setDataForm($me->id, 'author');
			$this->setDataForm($me->getFullName(), 'author_name');
		}
	}
}
