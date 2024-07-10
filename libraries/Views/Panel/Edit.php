<?php

namespace packages\news\Views\Panel;

use packages\news\Views\Form;
use packages\userpanel\Date;

class Edit extends Form
{
    public function setNew($new)
    {
        $this->setData($new, 'new');
        $this->setDataForm($new->toArray());
        $this->setDataForm(Date::format('Y/m/d H:i:s', $new->date), 'date');
    }

    public function getPost()
    {
        return $this->getData('new');
    }
}
