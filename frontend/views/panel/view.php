<?php
namespace themes\clipone\views\news\panel;
use \packages\base\frontend\theme;
use \packages\base\translator;
use \packages\base\packages;
use \packages\base\options;
use \packages\userpanel;
use \packages\news\newpost;
use \packages\news\views\panel\view as newView;
use \themes\clipone\navigation;
use \themes\clipone\navigation\menuItem;
use \themes\clipone\viewTrait;


class view extends newView{
	use viewTrait;
	protected $new;
	function __beforeLoad(){
		$this->new = $this->getNew();
		$this->setTitle($this->new->title);
		if(navigation::getByName("news")){
			navigation::active("news");
		}else{
			navigation::active("dashboard");
		}
		$this->addAssets();
	}
	protected function addAssets(){

	}
	function getImage(newpost $post){
		$newspackage = packages::package('news');
		return ($newspackage->url($post->image ? $post->image : options::get('packages.news.defaultimage')));
	}
}
