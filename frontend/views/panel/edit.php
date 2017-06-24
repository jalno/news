<?php
namespace themes\clipone\views\news\panel;
use \packages\news\views\panel\edit as newEdit;
use \packages\base\frontend\theme;
use \packages\base\translator;
use \packages\base\packages;
use \packages\base\options;
use \packages\userpanel\user;
use \themes\clipone\navigation;
use \themes\clipone\views\formTrait;
use \themes\clipone\views\listTrait;
use \themes\clipone\viewTrait;
use \packages\news\newpost;
use \packages\news\file;
use \packages\news\authorization;
class edit extends newEdit{
	use viewTrait, listTrait, formTrait;
	protected $post;
	function __beforeLoad(){
		$this->post = $this->getPost();
		$this->setTitle(array(
			translator::trans('news'),
			translator::trans('edit')
		));
		navigation::active("news");
		$this->addJSFile(theme::url("assets/plugins/ckeditor/ckeditor.js"));
		$this->setUserFormData();
		$this->setButtons();
	}
	protected function getImage(){
		$newspackage = packages::package('news');
		return ($newspackage->url($this->post->image ? $this->post->image : options::get('packages.news.defaultimage')));
	}
	protected function setUserFormData(){
		if($author = $this->getDataForm('author')){
			if($author = user::byId($author)){
				$this->setDataForm($author->getFullName(), 'author_name');
			}
		}
	}
	public function setButtons(){
		$this->setButton('file_link', true, array(
			'title' => translator::trans('news.post.files.link'),
			'icon' => 'fa fa-link',
			'classes' => array('btn', 'btn-xs', 'btn-info')
		));
		$this->setButton('file_delete', authorization::is_accessed('files_delete'), array(
			'title' => translator::trans('delete'),
			'icon' => 'fa fa-times',
			'classes' => array('btn', 'btn-xs', 'btn-bricky', 'btn-delete'),
		));
	}
	public function getFileSize(file $file){
		if($file->size < 1024){
			return translator::trans('news.files.size.byBytes', array('bytes' => $file->size));
		}else if($file->size < 1024*1024){
			return translator::trans('news.files.size.byKB', array('kb' => round($file->size / 1024)));
		}else if($file->size < 1024*1024*1024){
			return translator::trans('news.files.size.byMB', array('mb' => round($file->size / 1024 / 1024)));
		}else if($file->size < 1024*1024*1024*1024){
			return translator::trans('news.files.size.byGB', array('gb' => round($file->size / 1024 / 1024 / 1024)));
		}else{
			return translator::trans('news.files.size.byTB', array('tb' => round($file->size / 1024 / 1024 / 1024 / 1024)));
		}
	}
	public function getFileIcon(file $file){
		$ext = substr($file->name, -strrpos($file->name, '.')+1);
		switch($ext){
				case('html'):
				case('css'):
				case('xml'):
				case('js'):
				case('atom'):
				case('rss'):
				case('json'):
				case('perl'):
				case('xhtml'):
					return 'fa fa-file-code-o';
				case('gif'):
				case('png'):
				case('jpeg'):
				case('tiff'):
				case('ico'):
				case('bmp'):
				case('svg'):
				case('webp'):
					return 'fa fa-file-image-o';

				case('txt'):
				case('rtf'):
					return 'fa fa-file-text-o';
				case('doc'):
				case('docx'):
					return 'fa fa-file-word-o';
				case('xls'):
				case('xlsx'):
					return 'fa fa-file-excel-o';
				case('ppt'):
				case('pptx'):
					return 'fa fa-file-powerpoint-o';
				case('pdf'):
					return 'fa fa-file-pdf-o';
				case('zip'):
				case('rar'):
				case('7z'):
					return 'fa fa-file-archive-o';
				case('3gpp'):
				case('mp4'):
				case('mpeg'):
				case('webm'):
				case('flv'):
				case('m4v'):
				case('mng'):
				case('wmv'):
					return 'fa fa-file-video-o';
				case('mp3'):
				case('ogg'):
				case('m4a'):
					return 'fa fa-file-audio-o';
				default:
					return 'fa fa-file-o';
		}
	}
}
