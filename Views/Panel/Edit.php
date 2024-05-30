<?php
namespace packages\news\Views\Panel;
use \packages\userpanel\Date;
use \packages\news\Views\Form;
use \packages\news\Authorization;
class Edit extends Form{
	public function setNew($new){
		$this->setData($new, 'new');
		$this->setDataForm($new->toArray());
		$this->setDataForm(Date::format('Y/m/d H:i:s', $new->date), 'date');
	}
	public function getPost(){
		return $this->getData('new');
	}
}
