<?php
namespace packages\news\views\panel;
use \packages\userpanel\date;
use \packages\news\views\form;
use \packages\news\authorization;
class edit extends form{
	public function setNew($new){
		$this->setData($new, 'new');
		$this->setDataForm($new->toArray());
		$this->setDataForm(date::format('Y/m/d H:i:s', $new->date), 'date');
	}
	public function getPost(){
		return $this->getData('new');
	}
}
