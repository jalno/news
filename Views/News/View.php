<?php

namespace packages\news\Views\News;

use packages\news\NewPost;
use packages\news\Views\Form;

class View extends Form
{
    public function setNew(NewPost $new)
    {
        $this->setData($new, 'new');
    }

    public function getNew()
    {
        return $this->getData('new');
    }
}
