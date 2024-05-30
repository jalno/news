<?php
namespace themes\clipone\Views\News\Panel;
use \packages\news\Views\Panel\Add as NewADD;
use \packages\base\Translator;
use \packages\base\Options;
use \packages\base\Packages;
use \packages\userpanel\User;
use \themes\clipone\Navigation;
use \themes\clipone\Views\FormTrait;
use \themes\clipone\ViewTrait;
use \packages\news\Authentication;
use \packages\base\Frontend\Theme;
class Add extends NewADD{
	use ViewTrait, FormTrait;
	function __beforeLoad(){
		$this->setTitle(array(
			Translator::trans('news'),
			Translator::trans('add')
		));
		Navigation::active("news/addnew");
		$this->setUserInput();
		$this->addJSFile(Theme::url("assets/plugins/ckeditor/ckeditor.js"));
	}
	function getImage(){
		$newspackage = Packages::package('news');
		return ($newspackage->url(Options::get('packages.news.defaultimage')));
	}
	private function setUserInput(){
		if($error = $this->getFormErrorsByInput('author')){
			$error->setInput('author_name');
			$this->setFormError($error);
		}
		$user = $this->getDataForm('author');
		if($user){
			$user = User::byId($user);
			$this->setDataForm($user->getFullName(), 'author_name');
		}else{
			$me = Authentication::getUser();
			$this->setDataForm($me->id, 'author');
			$this->setDataForm($me->getFullName(), 'author_name');
		}
	}
}
