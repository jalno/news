<?php
namespace themes\clipone\Views\News\Panel;
use \packages\base\Translator;
use \packages\base\Packages;
use \packages\base\Options;
use \packages\news\NewPost as Post;
use \packages\news\Views\Panel\View as NewView;
use \themes\clipone\Navigation;
use \themes\clipone\ViewTrait;
class View extends NewView{
	use ViewTrait;
	protected $new;
	function __beforeLoad(){
		$this->new = $this->getNew();
		$this->setTitle($this->new->title);
		if(Navigation::getByName("news")){
			Navigation::active("news");
		}else{
			Navigation::active("dashboard");
		}
		$this->addAssets();
	}
	protected function addAssets(){

	}
	function getImage(Post $post){
		$newspackage = Packages::package('news');
		return ($newspackage->url($post->image ? $post->image : Options::get('packages.news.defaultimage')));
	}
}
