<?php
namespace themes\clipone\views\news\panel;
use \packages\news\views\panel\edit as newEdit;
use \packages\base\frontend\theme;
use \packages\base\translator;
use \packages\base\packages;
use \packages\base\options;
use \packages\userpanel;
use \themes\clipone\navigation;
use \themes\clipone\navigation\menuItem;
use \themes\clipone\views\formTrait;
use \themes\clipone\viewTrait;

use \packages\news\newpost;

class edit extends newEdit{
	use viewTrait,formTrait;
	function __beforeLoad(){
		$this->setTitle(array(
			translator::trans('news'),
			translator::trans('edit')
		));
		navigation::active("news");
		$this->addAssets();
	}
	protected function addAssets(){
		$this->addCSSFile(theme::url('assets/css/pages/news.edit.css'));
		$this->addJSFile(theme::url('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.js'));
		$this->addCSSFile(theme::url('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css'));
		$this->addJSFile(theme::url('assets/plugins/ckeditor/ckeditor.js'));
		$this->addJSFile(theme::url('assets/js/pages/new.edit.js'));
	}
	function getImage(newpost $post){
		$newspackage = packages::package('news');
		return ($newspackage->url($post->image ? $post->image : options::get('packages.news.defaultimage')));
	}
}
