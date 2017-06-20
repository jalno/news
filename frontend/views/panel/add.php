<?php
namespace themes\clipone\views\news\panel;
use \packages\news\views\panel\add as newADD;
use \packages\base\frontend\theme;
use \packages\base\translator;
use \packages\base\options;
use \packages\base\packages;
use \packages\userpanel;
use \packages\userpanel\user;
use \themes\clipone\navigation;
use \themes\clipone\navigation\menuItem;
use \themes\clipone\views\formTrait;
use \themes\clipone\viewTrait;

use \packages\news\authentication;
use \packages\news\newpost;

class add extends newADD{
	use viewTrait,formTrait;
	function __beforeLoad(){
		$this->setTitle(array(
			translator::trans('news'),
			translator::trans('add')
		));
		navigation::active("news/addnew");
		$this->addAssets();
		$this->setUserInput();
	}
	protected function addAssets(){
		$this->addCSSFile(theme::url('assets/css/pages/news.add.css'));
		$this->addJSFile(theme::url('assets/plugins/ckeditor/ckeditor.js'));
		$this->addJSFile(theme::url('assets/js/pages/new.add.js'));
		$this->addJSFile(theme::url('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.js'));
		$this->addCSSFile(theme::url('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css'));
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
