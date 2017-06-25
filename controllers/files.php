<?php
namespace packages\news\controllers;
use \packages\base;
use \packages\base\IO;
use \packages\base\packages;
use \packages\base\NotFound;
use \packages\base\db\dbObject;
use \packages\base\inputValidation;
use \packages\userpanel\controller;
use \packages\news\file;
use \packages\news\authorization;
class files extends controller{
	protected $authentication = true;
	public function upload(){
		authorization::haveOrFail('files_upload');
		$inputRules = array(
			'file' => array(
				'type' => 'file'
			)
		);
		$this->response->setStatus(false);
		try{
			$inputs = $this->checkinputs($inputRules);
			if(isset($inputs['file']['name'])){
				$inputs['file'] = array($inputs['file']);
			}
			$files = array();
			foreach($inputs['file'] as $key => $attachment){
				if($attachment['error'] == 0){
					$dir = packages::package('news')->getFilePath("storage/public/files/");
					if(!is_dir($dir)){
						IO\mkdir($dir, true);
					}
					$md5 = IO\md5($attachment['tmp_name']);
					$ext = '';
					if(($dot = strrpos($attachment['name'],".")) !== false){
						$ext = strtolower(substr($attachment['name'], $dot));
					}
					$filePath = $dir.$md5.$ext;
					if(move_uploaded_file($attachment['tmp_name'], $filePath)){
						$file = new file();
						$file->file = "storage/public/files/{$md5}{$ext}";
						$file->name = $attachment['name'];
						$file->size = $attachment['size'];
						$file->md5 = $md5;
						$file->save();
						$file = $file->toArray();
						$file['url'] = packages::package('news')->url($file['file'], true);
						$files[] = $file;
					}else{
						throw new inputValidation("file[{$key}]");
					}
				}
			}
			$this->response->setStatus(true);
			$this->response->setData($files, 'files');
		}catch(inputValidation $error){
			$this->response->setData(array(
				array(
					'type' => 'fatal',
					'error' => 'input_validation',
					'input' => $error->getInput()
				)
			), 'error');
		}
		return $this->response;
	}
	private function getFile($data){
		if(!$file = file::byID($data['file'])){
			throw new NotFound;
		}
		return $file;
	}
	public function delete($data){
		$file = $this->getFile($data);
		$file->delete();
		$this->response->setStatus(true);
		return $this->response;
	}
}
