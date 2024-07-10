<?php

namespace packages\news\Views\Panel;

use packages\news\Views\Form;

class Delete extends Form
{
    protected static $navigation;

    public function setNew($new)
    {
        $this->setData($new, 'new');
    }

    public function getNew()
    {
        return $this->getData('new');
    }
}
